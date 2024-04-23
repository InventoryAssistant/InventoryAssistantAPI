<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Location;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use \Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
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
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getProductsByLocation(Location $location): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {

        $products = Product::with(['location_products' => function ($query) use ($location) {
            return $query->where('location_id', $location->id);
        }])->get();

        return ProductResource::collection($products);
    }

    /**
     * Search products by barcode.
     * @param string $barcode
     *
     * @return JsonResponse | ProductResource
     */
    public function barcodeSearch(Int $barcode): JsonResponse | ProductResource
    {
        // Check if product exists in database
        $product = Product::where('barcode', $barcode)->first();
        if ($product) {
            // Return the product
            return new ProductResource($product);
        }

        // Salling API Bearer token
        $sallingToken = 'a5414649-0e40-4b8b-a489-d3c7adf1f8c7';

        // Salling API store id (Bilka -Odense)
        $storeId = 'f897964d-2890-49bb-90f6-86f12b11afe6';

        // Get products from Salling API
        $response = Http::withToken($sallingToken)->get('https://api.sallinggroup.com/v2/products/' . $barcode . '?storeId=' . $storeId);
        if ($response) {
            $data = $response->json();
            if (isset($data['instore'])) {
                return response()->json($data);
            }
        }

        // Return error message if product not found
        return response()->json(['message' => 'Product not found'], 404);
    }
}
