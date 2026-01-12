<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'icon' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        Category::create($validated);
        return redirect()->route('categories.index')->with('success', 'Danh mục đã được thêm thành công!');
    }

    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.show', compact('category'));
    }

    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'icon' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $category->update($validated);
        return redirect()->route('categories.index')->with('success', 'Danh mục đã được cập nhật!');
    }

    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Danh mục đã được xóa!');
    }

    public function toggleStatus(Category $category)
    {
        $category->is_active = !$category->is_active;
        $category->save();
        return back()->with('success', 'Đã cập nhật trạng thái danh mục.');
    }
}
