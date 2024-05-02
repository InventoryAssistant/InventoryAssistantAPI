<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return RoleResource::collection(Role::all());
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
     * @param RoleRequest $request
     * @return RoleResource
     */
    public function store(RoleRequest $request): RoleResource
    {
        $role = Role::create($request->all());

        Role::find($role->id)->role_abilities()->sync($request['abilities']);

        return new RoleResource($role);
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     * @return RoleResource
     */
    public function show(Role $role): RoleResource
    {
        return new RoleResource($role);
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
     * @param RoleRequest $request
     * @param Role $role
     * @return RoleResource
     */
    public function update(RoleRequest $request, Role $role): RoleResource
    {
        $role->update($request->all());

        Role::find($role->id)->role_abilities()->sync($request['abilities']);

        return new RoleResource($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return JsonResponse
     */
    public function destroy(Role $role): JsonResponse
    {
        $role->delete();

        return response()->json(["Role deleted"]);
    }

    /**
     * Get current user roles
     *
     * @return JsonResponse
     */
    public function getRoles(): JsonResponse
    {
        return response()->json(auth()->user()->role);
    }
}
