<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->latest()
            ->paginate(20);
        $users = \App\Models\User::all(['id', 'name']);
        return view('messages.index', compact('messages', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:messages,id',
        ]);

        Message::create([
            'user_id' => auth()->id(),
            'content' => $request->input('content'),
            'parent_id' => $request->input('parent_id'),
        ]);

        return back()->with('success', 'Pesan berhasil dikirim.');
    }

    public function edit(Message $message)
    {
        $this->authorizeAction($message);
        return view('messages.edit', compact('message'));
    }

    public function update(Request $request, Message $message)
    {
        $this->authorizeAction($message);

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message->update([
            'content' => $request->input('content'),
        ]);

        return redirect()->route('messages.index')->with('success', 'Pesan berhasil diperbarui.');
    }

    public function destroy(Message $message)
    {
        $this->authorizeAction($message);
        $message->delete();

        return back()->with('success', 'Pesan berhasil dihapus.');
    }

    protected function authorizeAction(Message $message)
    {
        if (auth()->user()->role !== 'admin' && auth()->id() !== $message->user_id) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }
    }
}
