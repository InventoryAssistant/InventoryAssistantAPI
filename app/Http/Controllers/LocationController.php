<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return LocationResource::collection(Location::all());
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
     * @param LocationRequest $request
     * @return LocationResource
     */
    public function store(LocationRequest $request): LocationResource
    {
        $location = Location::create($request->all());

        return new LocationResource($location);
    }

    /**
     * Display the specified resource.
     *
     * @param Location $location
     * @return LocationResource
     */
    public function show(Location $location): LocationResource
    {
        return new LocationResource($location);
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
     * @param LocationRequest $request
     * @param Location $location
     * @return LocationResource
     */
    public function update(LocationRequest $request, Location $location): LocationResource
    {
        $location->update($request->all());

        return new LocationResource($location);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Location $location
     * @return JsonResponse
     */
    public function destroy(Location $location): JsonResponse
    {
        $location->delete();

        return response()->json(["Location deleted"]);
    }

    /**
     * Search location by name.
     *
     * @param string $name
     * @param int $paginate
     * @return AnonymousResourceCollection
     */
    public function search(string $name, int $paginate = 10): AnonymousResourceCollection
    {
        $location = Location::search($name)->paginate($paginate);

        return LocationResource::collection($location);
    }
}
