<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $conversations = Message::where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->groupBy(function ($msg) {
                $otherId = $msg->sender_id === Auth::id() ? $msg->receiver_id : $msg->sender_id;
                return $otherId;
            })
            ->map(fn($msgs) => $msgs->first());

        return view('chat.index', compact('conversations'));
    }

    public function conversation(int $userId)
    {
        $otherUser = User::findOrFail($userId);

        $messages = Message::where(function ($q) use ($userId) {
            $q->where('sender_id', Auth::id())->where('receiver_id', $userId);
        })->orWhere(function ($q) use ($userId) {
            $q->where('sender_id', $userId)->where('receiver_id', Auth::id());
        })->with('sender')->oldest()->get();

        // Mark as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return view('chat.conversation', compact('messages', 'otherUser'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'type' => 'text',
        ]);

        $message->load('sender');

        event(new MessageSent($message));

        return response()->json([
            'id' => $message->id,
            'message' => $message->message,
            'sender_id' => $message->sender_id,
            'created_at' => $message->created_at->format('H:i'),
        ]);
    }

    public function unreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())->where('is_read', false)->count();
        return response()->json(['count' => $count]);
    }
}
