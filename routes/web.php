<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CallController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/otp', [AuthController::class, 'showOtp'])->name('otp.verify');
    Route::post('/otp', [AuthController::class, 'verifyOtp']);
    Route::post('/otp/resend', [AuthController::class, 'resendOtp'])->name('otp.resend');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Public home
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/model/{id}', [HomeController::class, 'modelProfile'])->name('model.profile')->whereNumber('id');

// Static pages
Route::get('/help-center', fn() => view('help-center'))->name('help-center');
Route::get('/safety', fn() => view('safety'))->name('safety');
Route::get('/contact', fn() => view('contact'))->name('contact');
Route::post('/contact', [HomeController::class, 'sendContact'])->name('contact.send');
Route::get('/privacy-policy', fn() => view('privacy-policy'))->name('privacy-policy');
Route::get('/terms', fn() => view('terms'))->name('terms');
Route::get('/refund-policy', fn() => view('refund-policy'))->name('refund-policy');

// Authenticated user routes
Route::middleware(['auth', 'role:user,model,admin'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'userDashboard'])->name('user.dashboard');
    Route::post('/favorites/{modelId}', [HomeController::class, 'toggleFavorite'])->name('favorites.toggle');
    Route::get('/favorites', [HomeController::class, 'favorites'])->name('favorites');

    // Wallet
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
    Route::post('/wallet/order', [WalletController::class, 'createOrder'])->name('wallet.order');
    Route::post('/wallet/verify', [WalletController::class, 'verifyPayment'])->name('wallet.verify');

    // Calls
    Route::get('/call/history', [CallController::class, 'history'])->name('call.history');
    Route::get('/call/check-balance', [CallController::class, 'checkBalance'])->name('call.check-balance');
    Route::post('/call/initiate', [CallController::class, 'initiate'])->name('call.initiate');
    Route::post('/call/{id}/accept', [CallController::class, 'accept'])->name('call.accept');
    Route::post('/call/{id}/reject', [CallController::class, 'reject'])->name('call.reject');
    Route::post('/call/{id}/end', [CallController::class, 'end'])->name('call.end');
    Route::get('/call/{id}/room', [CallController::class, 'room'])->name('call.room');

    // Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::get('/chat/unread', [ChatController::class, 'unreadCount'])->name('chat.unread');
    Route::get('/chat/{userId}', [ChatController::class, 'conversation'])->name('chat.conversation');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
});

// Model routes
Route::middleware(['auth', 'role:model'])->prefix('model')->name('model.')->group(function () {
    Route::get('/dashboard', [ModelController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile/edit', [ModelController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [ModelController::class, 'updateProfile'])->name('profile.update');
    Route::post('/toggle-online', [ModelController::class, 'toggleOnline'])->name('toggle-online');
    Route::post('/kyc/upload', [ModelController::class, 'uploadKyc'])->name('kyc.upload');
    Route::get('/earnings', [ModelController::class, 'earnings'])->name('earnings');
    Route::post('/withdrawal/request', [ModelController::class, 'requestWithdrawal'])->name('withdrawal.request');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
    Route::get('/models', [AdminController::class, 'models'])->name('models');
    Route::get('/models/create', [AdminController::class, 'createModel'])->name('models.create');
    Route::post('/models/create', [AdminController::class, 'storeModel'])->name('models.store');
    Route::post('/models/{id}/approve-kyc', [AdminController::class, 'approveKyc'])->name('models.approve-kyc');
    Route::post('/models/{id}/reject-kyc', [AdminController::class, 'rejectKyc'])->name('models.reject-kyc');
    Route::get('/withdrawals', [AdminController::class, 'withdrawals'])->name('withdrawals');
    Route::post('/withdrawals/{id}/approve', [AdminController::class, 'approveWithdrawal'])->name('withdrawals.approve');
    Route::post('/withdrawals/{id}/reject', [AdminController::class, 'rejectWithdrawal'])->name('withdrawals.reject');
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
});
