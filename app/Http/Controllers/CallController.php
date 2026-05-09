<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\User;
use App\Services\AgoraService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CallController extends Controller
{
    public function __construct(
        private AgoraService $agoraService,
        private WalletService $walletService
    ) {}

    public function initiate(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'call_type' => 'required|in:audio,video',
        ]);

        $caller = Auth::user();
        $receiver = User::with('modelProfile')->findOrFail($request->receiver_id);

        if (!$receiver->modelProfile) {
            return response()->json(['error' => 'Model not found.'], 404);
        }

        $pricePerMinute = $request->call_type === 'audio'
            ? $receiver->modelProfile->audio_price
            : $receiver->modelProfile->video_price;

        // Check minimum balance (1 minute)
        if (!$caller->hasEnoughBalance($pricePerMinute)) {
            return response()->json(['error' => 'Insufficient wallet balance. Please recharge.'], 402);
        }

        $channelName = $this->agoraService->generateChannelName($caller->id, $receiver->id);
        $token = $this->agoraService->generateToken($channelName, $caller->id);

        $call = Call::create([
            'caller_id' => $caller->id,
            'receiver_id' => $receiver->id,
            'call_type' => $request->call_type,
            'status' => 'initiated',
            'price_per_minute' => $pricePerMinute,
            'channel_name' => $channelName,
            'agora_token' => $token,
        ]);

        // Notify receiver via Pusher
        event(new \App\Events\IncomingCall($call));

        return response()->json([
            'call_id' => $call->id,
            'channel_name' => $channelName,
            'token' => $token,
            'app_id' => config('services.agora.app_id'),
            'call_type' => $request->call_type,
            'price_per_minute' => $pricePerMinute,
        ]);
    }

    public function accept(Request $request, int $callId)
    {
        $call = Call::where('receiver_id', Auth::id())
            ->where('id', $callId)
            ->where('status', 'initiated')
            ->firstOrFail();

        $call->update(['status' => 'accepted', 'started_at' => now()]);

        $receiverToken = $this->agoraService->generateToken($call->channel_name, Auth::id());

        return response()->json([
            'channel_name' => $call->channel_name,
            'token' => $receiverToken,
            'app_id' => config('services.agora.app_id'),
            'call_type' => $call->call_type,
        ]);
    }

    public function reject(int $callId)
    {
        $call = Call::where('receiver_id', Auth::id())
            ->where('id', $callId)
            ->firstOrFail();

        $call->update(['status' => 'rejected']);

        event(new \App\Events\CallStatusChanged($call));

        return response()->json(['success' => true]);
    }

    public function end(Request $request, int $callId)
    {
        $call = Call::where(function ($q) {
            $q->where('caller_id', Auth::id())->orWhere('receiver_id', Auth::id());
        })->where('id', $callId)->where('status', 'accepted')->firstOrFail();

        $duration = $request->duration ?? (now()->diffInSeconds($call->started_at));
        $minutes = ceil($duration / 60);
        $amount = $minutes * $call->price_per_minute;

        $caller = $call->caller;
        $actualAmount = min($amount, $caller->wallet_balance);

        $call->update([
            'status' => 'completed',
            'duration' => $duration,
            'amount' => $actualAmount,
            'ended_at' => now(),
        ]);

        if ($actualAmount > 0) {
            $this->walletService->deductForCall($caller, $actualAmount, $call->id);
            $this->walletService->creditModelEarning($call->receiver, $actualAmount, $call->id);
        }

        $call->receiver->modelProfile()->increment('total_calls');

        event(new \App\Events\CallStatusChanged($call));

        return response()->json([
            'success' => true,
            'duration' => $duration,
            'amount' => $actualAmount,
        ]);
    }

    public function history()
    {
        $calls = Call::where('caller_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->with(['caller', 'receiver'])
            ->latest()
            ->paginate(20);

        return view('call-history', compact('calls'));
    }

    public function room(int $callId)
    {
        $call = Call::where(function ($q) {
            $q->where('caller_id', Auth::id())->orWhere('receiver_id', Auth::id());
        })->findOrFail($callId);

        return view('call-room', compact('call'));
    }

    public function checkBalance(Request $request)
    {
        $user = Auth::user();
        $model = User::with('modelProfile')->findOrFail($request->model_id);
        $pricePerMinute = $request->call_type === 'audio'
            ? $model->modelProfile->audio_price
            : $model->modelProfile->video_price;

        return response()->json([
            'balance' => $user->wallet_balance,
            'price_per_minute' => $pricePerMinute,
            'can_call' => $user->wallet_balance >= $pricePerMinute,
            'minutes_available' => $pricePerMinute > 0 ? floor($user->wallet_balance / $pricePerMinute) : 0,
        ]);
    }
}
