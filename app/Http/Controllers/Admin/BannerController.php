<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::latest()->paginate(20);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'link' => 'nullable|string',
            'position' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        Banner::create($validated);
        return redirect()->route('banners.index')->with('success', 'Banner đã được thêm thành công!');
    }

    public function show(string $id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banners.show', compact('banner'));
    }

    public function edit(string $id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, string $id)
    {
        $banner = Banner::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'link' => 'nullable|string',
            'position' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $banner->update($validated);
        return redirect()->route('banners.index')->with('success', 'Banner đã được cập nhật!');
    }

    public function destroy(string $id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();
        return redirect()->route('banners.index')->with('success', 'Banner đã được xóa!');
    }

    public function toggleStatus(Banner $banner)
    {
        $banner->is_active = !$banner->is_active;
        $banner->save();
        return back()->with('success', 'Đã cập nhật trạng thái banner.');
    }
}
