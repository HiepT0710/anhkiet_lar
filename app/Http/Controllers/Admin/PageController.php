<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index() { $pages = Page::latest()->paginate(20); return view('admin.pages.index', compact('pages')); }
    public function create() { return view('admin.pages.create'); }
    public function store(Request $r) {
        $data = $r->validate(['title'=>'required','slug'=>'required|unique:pages,slug','content'=>'nullable','is_active'=>'boolean']);
        Page::create($data); return redirect()->route('pages.index')->with('success','Đã tạo trang'); }
    public function edit(Page $page) { return view('admin.pages.edit', compact('page')); }
    public function update(Request $r, Page $page) {
        $data = $r->validate(['title'=>'required','slug'=>'required|unique:pages,slug,'.$page->id,'content'=>'nullable','is_active'=>'boolean']);
        $page->update($data); return redirect()->route('pages.index')->with('success','Đã cập nhật trang'); }
    public function destroy(Page $page) { $page->delete(); return redirect()->route('pages.index')->with('success','Đã xóa trang'); }
    public function toggleStatus(Page $page) { $page->is_active = !$page->is_active; $page->save(); return back()->with('success','Đã cập nhật trạng thái'); }
}


