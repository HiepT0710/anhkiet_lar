<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AutoReply;
use Illuminate\Http\Request;

class AutoReplyController extends Controller
{
    public function index()
    {
        $autoReplies = AutoReply::orderBy('priority', 'desc')->paginate(20);
        return view('admin.auto-replies.index', compact('autoReplies'));
    }

    public function create()
    {
        return view('admin.auto-replies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'keywords' => 'required|string|max:500',
            'response' => 'required|string|max:2000',
            'priority' => 'nullable|integer|min:0|max:100',
        ]);

        AutoReply::create([
            'keywords' => $validated['keywords'],
            'response' => $validated['response'],
            'priority' => $validated['priority'] ?? 0,
            'is_active' => true,
        ]);

        return redirect()->route('admin.auto-replies.index')
            ->with('success', 'Đã thêm câu trả lời tự động!');
    }

    public function edit(AutoReply $autoReply)
    {
        return view('admin.auto-replies.edit', compact('autoReply'));
    }

    public function update(Request $request, AutoReply $autoReply)
    {
        $validated = $request->validate([
            'keywords' => 'required|string|max:500',
            'response' => 'required|string|max:2000',
            'priority' => 'nullable|integer|min:0|max:100',
        ]);

        $autoReply->update([
            'keywords' => $validated['keywords'],
            'response' => $validated['response'],
            'priority' => $validated['priority'] ?? 0,
        ]);

        return redirect()->route('admin.auto-replies.index')
            ->with('success', 'Đã cập nhật câu trả lời tự động!');
    }

    public function destroy(AutoReply $autoReply)
    {
        $autoReply->delete();

        return redirect()->route('admin.auto-replies.index')
            ->with('success', 'Đã xóa câu trả lời tự động!');
    }

    public function toggleStatus(AutoReply $autoReply)
    {
        $autoReply->update(['is_active' => !$autoReply->is_active]);

        return response()->json(['success' => true, 'is_active' => $autoReply->is_active]);
    }
}

