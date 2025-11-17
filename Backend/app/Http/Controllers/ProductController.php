<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Product service instance.
     *
     * @var ProductService
     */
    protected $productService;

    /**
     * Create a new controller instance.
     *
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $filters = [
            'category' => $request->get('category'),
            'status' => $request->get('status'),
            'search' => $request->get('search'),
            'min_price' => $request->get('min_price'),
            'max_price' => $request->get('max_price'),
            'sort' => $request->get('sort', 'created_at'),
            'order' => $request->get('order', 'desc'),
        ];

        $query = $this->productService->searchProducts($filters);
        $perPage = min($request->get('per_page', 15), 100);
        $products = $query->paginate($perPage);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductRequest $request
     * @return JsonResponse
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        
        // Handle image file if uploaded
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }

        $product = $this->productService->createProduct($data);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully.',
            'data' => new ProductResource($product)
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new ProductResource($product)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validated();
        
        // Handle image file if uploaded
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }

        $product = $this->productService->updateProduct($product, $data);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully.',
            'data' => new ProductResource($product)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $this->productService->deleteProduct($product);

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully.'
        ]);
    }
}
