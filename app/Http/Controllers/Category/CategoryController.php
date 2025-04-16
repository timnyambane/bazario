<?php

namespace App\Http\Controllers\Category;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::select('id', 'name', 'parent_id')->get();

        return response()->json([
            'success' => true,
            'message' => 'Category list retrieved successfully.',
            'data' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
            'children' => 'array',
            'children.*.name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error($validator->errors(), 422);
        }

        $category = Category::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        if ($request->has('children')) {
            foreach ($request->children as $child) {
                Category::create([
                    'name' => $child['name'],
                    'parent_id' => $category->id,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully.',
            'data' => $category,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return ApiResponse::error('Category not found.', 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error($category->errors(), 404);
        }

        $category->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return ApiResponse::success($category, 'Category updated successfully.', 200);
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return ApiResponse::error('Category not found.', 404);
        }

        if ($category->children->count() > 0) {
            return ApiResponse::error('Category has subcategories and cannot be deleted.', 422);
        }

        $category->delete();

        return ApiResponse::success([], 'Category deleted successfully.', 200);
    }
}
