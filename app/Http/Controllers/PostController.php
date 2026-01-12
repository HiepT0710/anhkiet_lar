<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('category')->where('is_active', true)->latest('published_at')->latest()->paginate(12);
        $categories = PostCategory::where('is_active', true)->get();
        return view('posts.index', compact('posts', 'categories'));
    }

    public function category(string $slug)
    {
        $category = PostCategory::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $posts = Post::where('post_category_id', $category->id)->where('is_active', true)->latest('published_at')->latest()->paginate(12);
        $categories = PostCategory::where('is_active', true)->get();
        return view('posts.category', compact('category', 'posts', 'categories'));
    }

    public function show(string $slug)
    {
        $post = Post::with('category')->where('slug', $slug)->where('is_active', true)->firstOrFail();
        $related = Post::where('post_category_id', $post->post_category_id)
            ->where('id', '!=', $post->id)
            ->where('is_active', true)
            ->latest('published_at')->latest()
            ->take(6)->get();
        return view('posts.show', compact('post', 'related'));
    }
}


