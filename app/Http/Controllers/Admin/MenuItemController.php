<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index() { $menuItems = MenuItem::orderBy('parent_id')->orderBy('order')->paginate(50); return view('admin.menus.index', compact('menuItems')); }
    public function create() { $parents = MenuItem::whereNull('parent_id')->get(); return view('admin.menus.create', compact('parents')); }
    public function store(Request $r) {
        $data = $r->validate(['title'=>'required','url'=>'required','parent_id'=>'nullable|integer','order'=>'required|integer|min:0','is_active'=>'boolean']);
        MenuItem::create($data); return redirect()->route('menus.index')->with('success','Đã tạo menu'); }
    public function edit(MenuItem $menu) { $parents = MenuItem::whereNull('parent_id')->where('id','!=',$menu->id)->get(); return view('admin.menus.edit', ['item'=>$menu,'parents'=>$parents]); }
    public function update(Request $r, MenuItem $menu) {
        $data = $r->validate(['title'=>'required','url'=>'required','parent_id'=>'nullable|integer','order'=>'required|integer|min:0','is_active'=>'boolean']);
        $menu->update($data); return redirect()->route('menus.index')->with('success','Đã cập nhật menu'); }
    public function destroy(MenuItem $menu) { $menu->delete(); return redirect()->route('menus.index')->with('success','Đã xóa menu'); }
    public function toggleStatus(MenuItem $menu) { $menu->is_active = !$menu->is_active; $menu->save(); return back()->with('success','Đã cập nhật trạng thái'); }
}


