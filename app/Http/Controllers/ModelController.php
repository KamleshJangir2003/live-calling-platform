<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\Transaction;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ModelController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $profile = $user->modelProfile()->firstOrCreate(['user_id' => $user->id]);

        $stats = [
            'total_calls' => Call::where('receiver_id', $user->id)->where('status', 'completed')->count(),
            'today_earnings' => Transaction::where('user_id', $user->id)
                ->where('type', 'earning')
                ->whereDate('created_at', today())
                ->sum('amount'),
            'total_earnings' => $profile->total_earnings ?? 0,
            'pending_withdrawal' => WithdrawalRequest::where('user_id', $user->id)->where('status', 'pending')->sum('amount'),
        ];

        $recentCalls = Call::where('receiver_id', $user->id)
            ->with('caller')
            ->latest()
            ->take(10)
            ->get();

        return view('model.dashboard', compact('user', 'profile', 'stats', 'recentCalls'));
    }

    public function editProfile()
    {
        $user = Auth::user()->load('modelProfile');
        return view('model.edit-profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'bio' => 'nullable|string|max:500',
            'country' => 'nullable|string',
            'languages' => 'nullable|string',
            'audio_price' => 'required|numeric|min:0.5|max:100',
            'video_price' => 'required|numeric|min:0.5|max:100',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();
        $data = $request->only(['bio', 'country', 'languages', 'audio_price', 'video_price']);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('model-photos', 'public');
            $data['profile_photo'] = $path;
        }

        $user->modelProfile()->update($data);
        $user->update(['country' => $request->country]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function toggleOnline()
    {
        $profile = Auth::user()->modelProfile;
        $profile->update(['online_status' => !$profile->online_status]);

        return response()->json([
            'online' => $profile->online_status,
            'message' => $profile->online_status ? 'You are now online' : 'You are now offline',
        ]);
    }

    public function uploadKyc(Request $request)
    {
        $request->validate(['document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120']);

        $path = $request->file('document')->store('kyc-documents', 'public');
        Auth::user()->modelProfile()->update([
            'kyc_document' => $path,
            'kyc_status' => 'pending',
        ]);

        return back()->with('success', 'KYC document uploaded. Awaiting approval.');
    }

    public function requestWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'bank_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'ifsc_code' => 'nullable|string',
            'upi_id' => 'nullable|string',
        ]);

        $user = Auth::user();

        if ($user->wallet_balance < $request->amount) {
            return back()->withErrors(['amount' => 'Insufficient balance.']);
        }

        WithdrawalRequest::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'ifsc_code' => $request->ifsc_code,
            'upi_id' => $request->upi_id,
        ]);

        return back()->with('success', 'Withdrawal request submitted.');
    }

    public function earnings()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->where('type', 'earning')
            ->latest()
            ->paginate(20);

        $withdrawals = WithdrawalRequest::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('model.earnings', compact('transactions', 'withdrawals'));
    }
}
