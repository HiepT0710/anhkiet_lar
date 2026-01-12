<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('category')->latest()->paginate(20);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = PostCategory::where('is_active', true)->get();
        return view('admin.posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:posts,slug',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'image' => 'nullable|string',
            'post_category_id' => 'required|exists:post_categories,id',
            'is_active' => 'boolean',
            'published_at' => 'nullable|date',
        ]);
        Post::create($data);
        return redirect()->route('posts.admin.index')->with('success', 'Đã tạo bài viết');
    }

    public function edit(Post $post)
    {
        $categories = PostCategory::where('is_active', true)->get();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:posts,slug,' . $post->id,
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'image' => 'nullable|string',
            'post_category_id' => 'required|exists:post_categories,id',
            'is_active' => 'boolean',
            'published_at' => 'nullable|date',
        ]);
        $post->update($data);
        return redirect()->route('posts.admin.index')->with('success', 'Đã cập nhật bài viết');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.admin.index')->with('success', 'Đã xóa bài viết');
    }

    public function toggleStatus(Post $post)
    {
        $post->is_active = !$post->is_active;
        $post->save();
        return back()->with('success', 'Đã cập nhật trạng thái bài viết');
    }

    public function show(string $slug)
    {
        $post = Post::with('category')->where('slug', $slug)->firstOrFail();
        return view('admin.posts.show', compact('post'));
    }
}


