<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\CategoryRequest;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::paginate(8);
        return view('admin.categories.index', compact('categories'));
    }


    public function create()
    {
        return view('admin.categories.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image',
        ]);

        $data = $request->only('name');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/categories', 'public');
        }

        Category::create($data);

        return redirect()->route('dashboard.categories.index')->with('success', 'Category created successfully.');
    }


    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }


    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            $data['image'] = $request->file('image')->store('uploads/categories', 'public');
        }

        $category->update($data);

        return redirect()->route('dashboard.categories.index')->with('success', 'Category updated successfully!');
    }


    public function destroy(Category $category)
    {
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }

    // تغيير حالة الفئة (مفعّل / غير مفعّل)
    public function toggleStatus($id)
    {
        $category = Category::findOrFail($id);
        $category->status = !$category->status;
        $category->save();

        return response()->json([
            'status' => true,
            'new_status' => $category->status ? 'Active' : 'Inactive',
        ]);
    }
}
