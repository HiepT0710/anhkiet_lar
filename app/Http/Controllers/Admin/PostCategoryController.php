<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class PostCategoryController extends Controller
{
    public function index()
    {
        $categories = PostCategory::latest()->paginate(20);
        return view('admin.post_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.post_categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:post_categories,slug',
            'is_active' => 'boolean',
        ]);
        PostCategory::create($data);
        return redirect()->route('post-categories.index')->with('success', 'Đã tạo chuyên mục');
    }

    public function edit(PostCategory $post_category)
    {
        return view('admin.post_categories.edit', ['category' => $post_category]);
    }

    public function update(Request $request, PostCategory $post_category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:post_categories,slug,' . $post_category->id,
            'is_active' => 'boolean',
        ]);
        $post_category->update($data);
        return redirect()->route('post-categories.index')->with('success', 'Đã cập nhật chuyên mục');
    }

    public function destroy(PostCategory $post_category)
    {
        $post_category->delete();
        return redirect()->route('post-categories.index')->with('success', 'Đã xóa chuyên mục');
    }

    public function toggleStatus(PostCategory $post_category)
    {
        $post_category->is_active = !$post_category->is_active;
        $post_category->save();
        return back()->with('success', 'Đã cập nhật trạng thái');
    }
}


