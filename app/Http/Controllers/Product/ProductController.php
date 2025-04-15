<?php

namespace App\Http\Controllers\Product;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->get();
        return ApiResponse::success($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1',
            'stock' => 'required|integer|min:1',
            'featured' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'string',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        $product = Product::create($validator->validated());

        return ApiResponse::success($product, 'Product created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product = Product::with('category.parent')->find($product->id);

        if (!$product) {
            return ApiResponse::error('Product not found', 404);
        }

        return ApiResponse::success($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|required|exists:categories,id',
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:1',
            'stock' => 'sometimes|required|integer|min:1',
            'featured' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'string',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        $product->update($validator->validated());

        return ApiResponse::success($product->fresh(), 'Product updated successfully');
    }

    /**
     * Search a specified resource from storage.
     */

    public function search(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return ApiResponse::error('Search query is required', 422);
        }

        $products = Product::where('name', 'like', "%$query%")
            ->orWhere('slug', 'like', "%$query%")
            ->orWhere('description', 'like', "%$query%")
            ->with('category')
            ->get();

        return ApiResponse::success($products, 'Search results');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return ApiResponse::error('Product not found', 404);
        }

        try {
            $product->delete();
            return ApiResponse::success([], 'Product deleted successfully');
        } catch (\Exception $e) {
            return ApiResponse::error('Product deletion failed. ' . $e->getMessage(), 500);
        }
    }

}
