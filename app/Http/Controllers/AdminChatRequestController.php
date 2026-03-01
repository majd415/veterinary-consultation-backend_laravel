<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatRequest;

class AdminChatRequestController extends Controller
{
    public function index()
    {
        $requests = ChatRequest::with(['user', 'vet'])->latest()->paginate(10);
        return view('admin.chats.index', compact('requests'));
    }

    public function update(Request $request, $id)
    {
        $chat = ChatRequest::findOrFail($id);
        $chat->update($request->only(['status']));
        return redirect()->back()->with('success', 'Chat request updated');
    }

    public function destroy($id)
    {
        ChatRequest::destroy($id);
        return redirect()->back()->with('success', 'Chat request removed');
    }
}
