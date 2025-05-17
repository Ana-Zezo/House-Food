<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\Admin\CategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return ApiResponse::sendResponse(true, 'Data Retrieve Successful', CategoryResource::collection($categories));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = 'storage/' . $request->file('image')->store('uploads/images/categories', 'public');
        }

        $category = Category::create($data);
        return ApiResponse::sendResponse(true, 'Data Retrieve Successful', new CategoryResource($category));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::delete($category->image);
            }

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $data['image'] = 'storage/' . $request->file('image')->store('uploads/images/categories', 'public');
            }
        }

        $category->update($data);
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->image) {
            Storage::delete($category->image); // Delete image from storage
        }
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}