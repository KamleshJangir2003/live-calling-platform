<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Call;
use App\Models\ModelProfile;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_models' => User::where('role', 'model')->count(),
            'total_calls' => Call::where('status', 'completed')->count(),
            'today_revenue' => Transaction::where('type', 'recharge')
                ->where('status', 'completed')
                ->whereDate('created_at', today())
                ->sum('amount'),
            'total_revenue' => Transaction::where('type', 'recharge')->where('status', 'completed')->sum('amount'),
            'pending_kyc' => ModelProfile::where('kyc_status', 'pending')->count(),
            'pending_withdrawals' => WithdrawalRequest::where('status', 'pending')->count(),
            'admin_wallet' => User::where('role', 'admin')->first()?->wallet_balance ?? 0,
            'total_commission' => Transaction::where('type', 'commission')->where('status', 'completed')->sum('amount'),
        ];

        $recentTransactions = Transaction::with('user')->latest()->take(10)->get();
        $recentCalls = Call::with(['caller', 'receiver'])->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentTransactions', 'recentCalls'));
    }

    public function users(Request $request)
    {
        $query = User::where('role', 'user');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
                    ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function models(Request $request)
    {
        $query = User::where('role', 'model')->with('modelProfile');

        if ($request->kyc_status) {
            $query->whereHas('modelProfile', fn($q) => $q->where('kyc_status', $request->kyc_status));
        }

        $models = $query->latest()->paginate(20);
        return view('admin.models', compact('models'));
    }

    public function approveKyc(int $userId)
    {
        $profile = ModelProfile::where('user_id', $userId)->firstOrFail();
        $profile->update(['kyc_status' => 'approved']);
        User::find($userId)->update(['status' => 'active']);

        return back()->with('success', 'KYC approved successfully.');
    }

    public function rejectKyc(Request $request, int $userId)
    {
        ModelProfile::where('user_id', $userId)->update(['kyc_status' => 'rejected']);
        return back()->with('success', 'KYC rejected.');
    }

    public function toggleUserStatus(int $userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['status' => $user->status === 'active' ? 'banned' : 'active']);
        return back()->with('success', 'User status updated.');
    }

    public function withdrawals(Request $request)
    {
        $query = WithdrawalRequest::with('user');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->latest()->paginate(20);
        return view('admin.withdrawals', compact('withdrawals'));
    }

    public function approveWithdrawal(Request $request, int $id)
    {
        $withdrawal = WithdrawalRequest::findOrFail($id);
        $user = $withdrawal->user;

        $user->deductBalance($withdrawal->amount);
        $withdrawal->update([
            'status' => 'paid',
            'processed_at' => now(),
            'admin_note' => $request->note,
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'amount' => $withdrawal->amount,
            'type' => 'withdrawal',
            'status' => 'completed',
            'description' => 'Withdrawal processed by admin',
            'balance_before' => $user->wallet_balance + $withdrawal->amount,
            'balance_after' => $user->wallet_balance,
        ]);

        return back()->with('success', 'Withdrawal approved and processed.');
    }

    public function rejectWithdrawal(Request $request, int $id)
    {
        WithdrawalRequest::findOrFail($id)->update([
            'status' => 'rejected',
            'admin_note' => $request->note,
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Withdrawal rejected.');
    }

    public function transactions(Request $request)
    {
        $query = Transaction::with('user');

        if ($request->type) $query->where('type', $request->type);
        if ($request->date_from) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->date_to) $query->whereDate('created_at', '<=', $request->date_to);

        $transactions = $query->latest()->paginate(30);
        return view('admin.transactions', compact('transactions'));
    }

    public function settings()
    {
        $settings = [
            'commission_rate' => Setting::get('commission_rate', 20),
            'min_withdrawal' => Setting::get('min_withdrawal', 100),
            'chat_price' => Setting::get('chat_price', 1),
            'site_name' => Setting::get('site_name', 'LiveCall'),
        ];
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
            'min_withdrawal' => 'required|numeric|min:0',
            'chat_price' => 'required|numeric|min:0',
            'site_name' => 'required|string|max:100',
        ]);

        foreach ($request->only(['commission_rate', 'min_withdrawal', 'chat_price', 'site_name']) as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Settings updated.');
    }

    public function createModel()
    {
        return view('admin.create-model');
    }

    public function storeModel(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'email'         => 'required|email|unique:users,email',
            'phone'         => 'required|string|unique:users,phone',
            'password'      => 'required|min:6',
            'audio_price'   => 'required|numeric|min:0',
            'video_price'   => 'required|numeric|min:0',
            'country'       => 'nullable|string|max:100',
            'bio'           => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'password'          => \Hash::make($request->password),
            'role'              => 'model',
            'status'            => 'active',
            'phone_verified'    => true,
            'email_verified_at' => now(),
        ]);

        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('model-photos', 'public');
        }

        ModelProfile::create([
            'user_id'       => $user->id,
            'audio_price'   => $request->audio_price,
            'video_price'   => $request->video_price,
            'country'       => $request->country,
            'bio'           => $request->bio,
            'kyc_status'    => 'approved',
            'profile_photo' => $photoPath,
        ]);

        return redirect()->route('admin.models')->with('success', 'Model created successfully.');
    }

    public function reports()
    {
        $monthlyRevenue = Transaction::where('type', 'recharge')
            ->where('status', 'completed')
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        $topModels = User::where('role', 'model')
            ->with('modelProfile')
            ->get()
            ->sortByDesc(fn($u) => $u->modelProfile->total_earnings ?? 0)
            ->take(10);

        return view('admin.reports', compact('monthlyRevenue', 'topModels'));
    }
}
