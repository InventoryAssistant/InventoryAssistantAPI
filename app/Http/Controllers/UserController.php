<?php

namespace App\Http\Controllers;

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
    public function store(UserRequest $request)
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
        if (!\Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Login information is invalid.'
            ], 401);
        }

        // get user
        $user = User::where('email', $request['email'])->firstOrFail();

        // get current time and add an hour
        $expirationsDate = Carbon::now()->addHour();

        // create abilities based on user role
        $abilities = $user->role->role_abilities->pluck('name')->toArray();

        // create token with the given abilities
        $token = $user->createToken('authToken', $abilities, $expirationsDate)->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Logout user
     *
     * @return void
     */
    public function logout()
    {
        if (auth('sanctum')->check()) {
            auth('sanctum')->user()->tokens()->delete();
        }
    }

    /**
     * Check users token for ability
     *
     * @param Request $request
     * @param $ability
     * @return JsonResponse
     */
    public function checkAbility($ability) // TODO: RETURNS RECORD NOT FOUND
    {
        if (auth('sanctum')->check()) {
            //return $request->user('sanctum')->currentAccessToken()->tokenCan($ability);

            $user = auth('sanctum')->user();
            $token = $user->tokens()->first();
            $abilities = $token->abilities;

            // return $ability;
            // return $abilities;
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
}
