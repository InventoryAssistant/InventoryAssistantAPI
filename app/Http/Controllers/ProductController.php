<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Location;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return ProductResource::collection(Product::all());
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
     * @param ProductRequest $request
     * @return ProductResource
     */
    public function store(ProductRequest $request): ProductResource
    {
        $category = Product::create($request->all());

        return new ProductResource($category);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return ProductResource
     */
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
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
     * @param ProductRequest $request
     * @param Product $product
     * @return ProductResource
     */
    public function update(ProductRequest $request, Product $product): ProductResource
    {
        $product->update($request->all());

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json(["Product deleted"]);
    }

    /**
     * Get all products with specified location.
     *
     * @param Location $location
     * @return AnonymousResourceCollection
     */
    public function getProductsByLocation(Location $location): AnonymousResourceCollection
    {

        $products = Product::with(['location_products' => function ($query) use ($location) {
            return $query->where('location_id', $location->id);
        }])->get();

        return ProductResource::collection($products);
    }

    /**
     * Search product by name or barcode.
     *
     * @param string $search
     * @return AnonymousResourceCollection
     */
    public function quickSearch(string $search): AnonymousResourceCollection
    {
        $product = Product::search($search)->take(10)->get();

        return ProductResource::collection($product);
    }
}
