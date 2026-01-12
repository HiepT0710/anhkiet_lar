<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()->paginate(20);
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:brands,slug',
            'logo' => 'nullable|string',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        Brand::create($validated);
        return redirect()->route('brands.index')->with('success', 'Thêm thương hiệu thành công');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:brands,slug,' . $brand->id,
            'logo' => 'nullable|string',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $brand->update($validated);
        return redirect()->route('brands.index')->with('success', 'Cập nhật thương hiệu thành công');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return redirect()->route('brands.index')->with('success', 'Đã xóa thương hiệu');
    }

    public function toggleStatus(Brand $brand)
    {
        $brand->is_active = !$brand->is_active;
        $brand->save();
        return back()->with('success', 'Đã cập nhật trạng thái thương hiệu');
    }
}


