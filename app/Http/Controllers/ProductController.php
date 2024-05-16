<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Location;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use \Illuminate\Http\JsonResponse;
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
        $product = Product::create($request->all());

        $data = array();

        foreach ($request['locations'] as $location) {
            $data[$location['id']] = [
                'stock' => $location['stock'],
                'shelf_amount' => $location['shelf_amount']
            ];
        }

        Product::find($product->id)->location_products()->sync($data);

        return new ProductResource($product);
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

        $data = array();

        foreach ($request['locations'] as $location) {
            $data[$location['id']] = [
                'stock' => $location['stock'],
                'shelf_amount' => $location['shelf_amount']
            ];
        }

        Product::find($product->id)->location_products()->sync($data, false);

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
     * @param Request $request
     * @param Location $location
     * @return AnonymousResourceCollection
     */
    public function getProductsByLocation(Request $request, Location $location): AnonymousResourceCollection
    {
        // Request paginate field with default value of 10
        $paginate = request('paginate', 10);
        $category_id = request('category_id');

        // Validate input
        $request->validate([
            'paginate' => 'numeric',
            'category_id' => 'numeric|exists:categories,id'
        ]);

        $products = Product::with([
            'location_products' => function ($query) use ($location) {
                return $query->where('location_id', $location->id);
            }
        ])->when($request->get('category_id'), function ($query) use ($category_id) {
            $query->where('category_id', $category_id);
        })->simplePaginate($paginate);

        return ProductResource::collection($products);
    }

    /**
     * Get all products with user location.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function getProductsByUserLocation(Request $request): AnonymousResourceCollection
    {
        // Request paginate field with default value of 10
        $paginate = request('paginate', 10);
        $category_id = request('category_id');

        // Validate input
        $request->validate([
            'paginate' => 'numeric',
            'category_id' => 'numeric|exists:categories,id'
        ]);

        // Get the user
        $user = auth('sanctum')->user();

        // Get the location
        $location = $user['location_id'];

        // Get the products and only their pivot data from the users location
        $products = Product::with([
            'location_products' => function ($query) use ($location) {
                return $query->where('location_id', $location);
            }
        ])->orderBy('name')->when($request->get('category_id'), function ($query) use ($category_id) {
            $query->where('category_id', $category_id);
        })->simplePaginate($paginate);

        return ProductResource::collection($products);
    }

    /**
     * Search products by barcode.
     * @param string $barcode
     *
     * @return JsonResponse | ProductResource
     */
    public function barcodeSearch(int $barcode): JsonResponse|ProductResource
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
        $response = Http::withToken($sallingToken)->get(
            'https://api.sallinggroup.com/v2/products/' . $barcode . '?storeId=' . $storeId
        );
        if ($response) {
            $data = $response->json();
            if (isset($data['instore'])) {
                return response()->json($data);
            }
        }

        // Return error message if product not found
        return response()->json(['message' => 'Product not found'], 404);
    }

    /**
     * Search product by name or barcode.
     *
     * @param Request $request
     * @param string $search
     * @return AnonymousResourceCollection
     */
    public function search(Request $request, string $search): AnonymousResourceCollection
    {
        // Request paginate field with default value of 10
        $paginate = request('paginate', 10);

        // Validate input
        $request->validate([
            'paginate' => 'numeric',
        ]);

        $product = Product::search($search)->simplePaginate($paginate);

        return ProductResource::collection($product);
    }
}
