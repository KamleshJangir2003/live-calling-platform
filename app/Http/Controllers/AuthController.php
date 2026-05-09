<?php

namespace App\Http\Controllers;

use App\Models\ModelProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:user,model',
        ]);

        $otp = rand(100000, 999999);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        if ($request->role === 'model') {
            ModelProfile::create(['user_id' => $user->id]);
        }

        // In production, send OTP via SMS
        session(['otp_user_id' => $user->id]);

        return redirect()->route('otp.verify')->with('info', "OTP sent to {$request->phone}. Demo OTP: {$otp}");
    }

    public function showOtp()
    {
        if (!session('otp_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $user = User::find(session('otp_user_id'));

        if (!$user || $user->otp !== $request->otp || now()->isAfter($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        $user->update(['phone_verified' => true, 'otp' => null]);
        session()->forget('otp_user_id');

        Auth::login($user);

        return redirect()->intended($this->redirectPath($user));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended($this->redirectPath(Auth::user()));
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function resendOtp(Request $request)
    {
        $user = User::find(session('otp_user_id'));
        if (!$user) return redirect()->route('login');

        $otp = rand(100000, 999999);
        $user->update(['otp' => $otp, 'otp_expires_at' => now()->addMinutes(10)]);

        return back()->with('info', "New OTP sent. Demo OTP: {$otp}");
    }

    private function redirectPath(User $user): string
    {
        return match ($user->role) {
            'admin' => route('admin.dashboard'),
            'model' => route('model.dashboard'),
            default => route('home'),
        };
    }
}
