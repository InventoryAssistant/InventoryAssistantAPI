<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnitRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return UnitResource::collection(Unit::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UnitRequest $request
     * @return UnitResource
     */
    public function store(UnitRequest $request): UnitResource
    {
        $unit = Unit::create($request->all());

        return new UnitResource($unit);
    }

    /**
     * Display the specified resource.
     *
     * @param Unit $unit
     * @return UnitResource
     */
    public function show(Unit $unit): UnitResource
    {
        return new UnitResource($unit);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UnitRequest $request
     * @param Unit $unit
     * @return UnitResource
     */
    public function update(UnitRequest $request, Unit $unit)
    {
        $unit->update($request->all());

        return new UnitResource($unit);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Unit $unit
     * @return JsonResponse
     */
    public function destroy(Unit $unit): JsonResponse
    {
        $unit->delete();

        return response()->json(["Unit deleted"]);
    }
}
