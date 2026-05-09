<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\ModelProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function userDashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'total_calls' => \App\Models\Call::where('caller_id', $user->id)->where('status', 'completed')->count(),
            'total_spent' => \App\Models\Transaction::where('user_id', $user->id)->where('type', 'debit')->sum('amount'),
            'favorite_models' => Favorite::where('user_id', $user->id)->count(),
        ];

        $recentCalls = \App\Models\Call::where('caller_id', $user->id)
            ->with('receiver.modelProfile')
            ->latest()
            ->take(5)
            ->get();

        $favoriteModels = Favorite::where('user_id', $user->id)
            ->with('model.modelProfile')
            ->latest()
            ->take(6)
            ->get();

        return view('user.dashboard', compact('user', 'stats', 'recentCalls', 'favoriteModels'));
    }

    public function index(Request $request)
    {
        $query = User::where('role', 'model')
            ->where('status', 'active')
            ->whereHas('modelProfile', fn($q) => $q->where('kyc_status', 'approved'))
            ->with('modelProfile');

        if ($request->country) {
            $query->whereHas('modelProfile', fn($q) => $q->where('country', $request->country));
        }
        if ($request->language) {
            $query->whereHas('modelProfile', fn($q) => $q->where('languages', 'like', "%{$request->language}%"));
        }
        if ($request->online) {
            $query->whereHas('modelProfile', fn($q) => $q->where('online_status', true));
        }
        if ($request->call_type === 'audio') {
            $query->whereHas('modelProfile', fn($q) => $q->where('audio_price', '>', 0));
        } elseif ($request->call_type === 'video') {
            $query->whereHas('modelProfile', fn($q) => $q->where('video_price', '>', 0));
        }
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhereHas('modelProfile', fn($q2) => $q2->where('country', 'like', "%{$request->search}%")
                      ->orWhere('languages', 'like', "%{$request->search}%"));
            });
        }

        // Sorting
        switch ($request->sort) {
            case 'rating':
                $query->join('model_profiles as mp_sort', 'users.id', '=', 'mp_sort.user_id')
                      ->orderBy('mp_sort.rating', 'desc')->select('users.*');
                break;
            case 'calls':
                $query->join('model_profiles as mp_sort', 'users.id', '=', 'mp_sort.user_id')
                      ->orderBy('mp_sort.total_calls', 'desc')->select('users.*');
                break;
            case 'price_low':
                $query->join('model_profiles as mp_sort', 'users.id', '=', 'mp_sort.user_id')
                      ->orderBy('mp_sort.audio_price', 'asc')->select('users.*');
                break;
            case 'price_high':
                $query->join('model_profiles as mp_sort', 'users.id', '=', 'mp_sort.user_id')
                      ->orderBy('mp_sort.audio_price', 'desc')->select('users.*');
                break;
            default:
                // Online first, then by rating
                $query->join('model_profiles as mp_sort', 'users.id', '=', 'mp_sort.user_id')
                      ->orderBy('mp_sort.online_status', 'desc')
                      ->orderBy('mp_sort.rating', 'desc')
                      ->select('users.*');
        }

        $models = $query->paginate(16)->withQueryString();

        $favoriteIds = Auth::check()
            ? Favorite::where('user_id', Auth::id())->pluck('model_id')->toArray()
            : [];

        $countries = ModelProfile::distinct()->pluck('country')->filter()->sort()->values();

        return view('home', compact('models', 'favoriteIds', 'countries'));
    }

    public function modelProfile(int $id)
    {
        $model = User::where('role', 'model')
            ->where('status', 'active')
            ->with('modelProfile')
            ->findOrFail($id);

        $isFavorite = Auth::check()
            ? Favorite::where('user_id', Auth::id())->where('model_id', $id)->exists()
            : false;

        $recentCalls = Auth::check()
            ? $model->callsReceived()->where('caller_id', Auth::id())->latest()->take(5)->get()
            : collect();

        return view('model-profile', compact('model', 'isFavorite', 'recentCalls'));
    }

    public function toggleFavorite(int $modelId)
    {
        $existing = Favorite::where('user_id', Auth::id())->where('model_id', $modelId)->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['favorited' => false]);
        }

        Favorite::create(['user_id' => Auth::id(), 'model_id' => $modelId]);
        return response()->json(['favorited' => true]);
    }

    public function favorites()
    {
        $favorites = Favorite::where('user_id', Auth::id())
            ->with('model.modelProfile')
            ->latest()
            ->paginate(12);

        return view('favorites', compact('favorites'));
    }

    public function sendContact(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string|min:10|max:2000',
        ]);

        // Log the contact message (mail can be configured later)
        \Illuminate\Support\Facades\Log::info('Contact Form Submission', $request->only('name', 'email', 'subject', 'message'));

        return back()->with('success', 'Your message has been sent! We will respond within 24 hours.');
    }
}
