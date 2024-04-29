<?php

namespace App\Http\Controllers;

use App\Enums\TokenEnum;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Location;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return UserResource::collection(User::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return UserResource
     */
    public function store(UserRequest $request): UserResource
    {
        $user = User::create($request->all());

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return void
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param User $user
     * @return UserResource
     */
    public function update(UserRequest $request, User $user): UserResource
    {
        $user->update($request->all());

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user): \Illuminate\Http\JsonResponse
    {
        $user->delete();

        return response()->json(["User deleted"]);
    }

    /**
     * Get all users with specified location
     *
     * @param Location $location
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getUsersByLocation(Location $location): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return UserResource::collection(User::all()->where('location_id', $location->id));
    }

    /**
     * Register user
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function register(UserRequest $request): JsonResponse
    {
        // create the user
        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'phone_number' => $request->input('phone_number'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'location_id' => $request->input('location_id'),
            'role_id' => 1
        ]);

        return $this->login($request);
    }

    /**
     * Login user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Login information is invalid.'
            ], 401);
        }

        // get user
        $user = User::where('email', $request['email'])->firstOrFail();

        $token = self::createToken($user);

        return response()->json([
            'refresh_token' => $token['refresh_token'],
            'access_token' => $token['access_token'],
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Refresh user token
     * @param Request $request
     * @return JsonResponse
     */
    public function refresh(Request $request): JsonResponse
    {
        // refresh the users bearer token
        $user = auth('sanctum')->user();
        $token = self::createToken($user, true);

        return response()->json([
            'refresh_token' => $token['refresh_token'],
            'access_token' => $token['access_token'],
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Logout user
     *
     * @return void
     */
    public function logout(): void
    {
        if (auth('sanctum')->check()) {
            auth('sanctum')->user()->tokens()->delete();
        }
    }

    /**
     * Create token for user
     *
     * @param User $user
     * @param bool $refresh
     * @return array
     */
    private function createToken(User $user, bool $refresh = false): array
    {
        // get current time and add an hour
        $expirationsDate = Carbon::now()->addHour();

        // create abilities based on user role
        $abilities = $user->role->role_abilities->pluck('name')->toArray();

        // create token with the given abilities
        $token = $user->createToken('authToken', $abilities, $expirationsDate)->plainTextToken;

        if (!$refresh) {
            $refreshToken = $user->createToken('refresh_token', [TokenEnum::ISSUE_TOKENS->value], $expirationsDate->addDays(7))->plainTextToken;
        } else {
            $refreshToken = $user->tokens()->where('name', 'refresh_token')->first()->token;
        }

        return [
            'access_token' => $token,
            'refresh_token' => $refreshToken
        ];
    }

    /**
     * Check users token for ability
     *
     * @param Request $request
     * @param $ability
     * @return JsonResponse
     */
    public function checkAbility($ability): JsonResponse
    {
        // Check if a user is logged in
        if (auth('sanctum')->check()) {
            // Get the user
            $user = auth('sanctum')->user();

            // Get the first of the users tokens
            $token = $user->tokens()->first();

            // Get the abilities of the token
            $abilities = $token->abilities;

            // Check if the requested ability is in the tokens abilities
            if (in_array($ability, $abilities)) {
                return response()->json([
                    'has_ability' => true
                ]);
            }
            return response()->json([
                'has_ability' => false
            ]);
        } else {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 403);
        }
    }

    /**
     * Check if the token is valid
     *
     * @return JsonResponse
     */
    public function validateToken(): JsonResponse
    {
        return response()->json([
            'message' => 'Token is valid'
        ]);
    }
}
