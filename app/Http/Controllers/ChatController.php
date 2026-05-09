<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\Setting;
use App\Models\Transaction;
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

        // Only allow user<->model conversations
        $me = Auth::user();
        if ($me->isUser() && !$otherUser->isModel()) {
            abort(403, 'You can only chat with models.');
        }
        if ($me->isModel() && !$otherUser->isUser() && !$otherUser->isAdmin()) {
            abort(403, 'Invalid conversation.');
        }

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

        $user = Auth::user();
        $chatPrice = (float) Setting::get('chat_price', 1); // ₹1 per message default

        if ($user->isUser() && !$user->hasEnoughBalance($chatPrice)) {
            return response()->json([
                'error' => 'insufficient_balance',
                'message' => 'Message bhejne ke liye wallet recharge karein.',
                'balance' => $user->wallet_balance,
                'required' => $chatPrice,
            ], 402);
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'type' => 'text',
        ]);

        $message->load('sender');

        // Deduct chat charge for regular users and credit admin
        if ($user->isUser() && $chatPrice > 0) {
            $balanceBefore = $user->wallet_balance;
            $user->deductBalance($chatPrice);
            Transaction::create([
                'user_id' => $user->id,
                'amount' => $chatPrice,
                'type' => 'chat_deduction',
                'status' => 'completed',
                'description' => 'Chat message charge',
                'balance_before' => $balanceBefore,
                'balance_after' => $user->fresh()->wallet_balance,
            ]);

            // Credit full chat charge to admin
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $adminBefore = $admin->wallet_balance;
                $admin->addBalance($chatPrice);
                Transaction::create([
                    'user_id' => $admin->id,
                    'amount' => $chatPrice,
                    'type' => 'commission',
                    'status' => 'completed',
                    'description' => 'Chat charge from user #' . $user->id,
                    'balance_before' => $adminBefore,
                    'balance_after' => $admin->fresh()->wallet_balance,
                ]);
            }
        }

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
