@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h5 class="fw-bold mb-0">Welcome, {{ auth()->user()->name }}</h5>
    <small class="text-muted">User Dashboard</small>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="card p-3 text-center">
            <div class="fs-3 fw-bold text-primary">{{ $stats['total_calls'] }}</div>
            <small class="text-muted">Total Calls</small>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card p-3 text-center">
            <div class="fs-3 fw-bold text-success">₹{{ number_format($stats['total_spent'], 2) }}</div>
            <small class="text-muted">Total Spent</small>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card p-3 text-center">
            <div class="fs-3 fw-bold text-info">{{ $stats['favorite_models'] }}</div>
            <small class="text-muted">Favorites</small>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3">Quick Actions</h6>
        <div class="row g-2">
            <div class="col-6 col-md-3">
                <a href="{{ route('home') }}" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search me-1"></i>Browse Models
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('favorites') }}" class="btn btn-outline-danger w-100">
                    <i class="bi bi-heart-fill me-1"></i>My Favorites
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('wallet') }}" class="btn btn-outline-success w-100">
                    <i class="bi bi-wallet2 me-1"></i>Wallet
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('call.history') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-clock-history me-1"></i>Call History
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Wallet Balance -->
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-1">Wallet Balance</h6>
                <div class="fs-3 fw-bold text-success">₹{{ number_format(auth()->user()->wallet_balance, 2) }}</div>
            </div>
            <a href="{{ route('wallet') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Add Money
            </a>
        </div>
    </div>
</div>

<!-- Recent Calls -->
<div class="card mb-4">
    <div class="card-header bg-white border-0 pt-3">
        <h6 class="fw-bold mb-0">Recent Calls</h6>
    </div>
    <div class="card-body p-0">
        @if($recentCalls->isEmpty())
            <div class="text-center py-4 text-muted">
                <i class="bi bi-telephone-x fs-1"></i>
                <p class="mt-2">No calls yet. <a href="{{ route('home') }}">Browse models</a> to start calling!</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Model</th><th>Type</th><th>Duration</th><th>Cost</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        @foreach($recentCalls as $call)
                            <tr>
                                <td>
                                    <a href="{{ route('model.profile', $call->receiver_id) }}" class="text-decoration-none">
                                        {{ $call->receiver->name }}
                                    </a>
                                </td>
                                <td><span class="badge {{ $call->call_type === 'video' ? 'bg-warning text-dark' : 'bg-primary' }}">{{ ucfirst($call->call_type) }}</span></td>
                                <td>{{ $call->duration_formatted }}</td>
                                <td class="text-danger fw-bold">₹{{ number_format($call->amount, 2) }}</td>
                                <td class="small text-muted">{{ $call->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Favorite Models -->
@if($favoriteModels->isNotEmpty())
<div class="card">
    <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0">Favorite Models</h6>
        <a href="{{ route('favorites') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @foreach($favoriteModels as $favorite)
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('model.profile', $favorite->model_id) }}" class="text-decoration-none">
                        <div class="text-center">
                            <img src="{{ $favorite->model->modelProfile->profile_photo_url }}" 
                                 class="rounded-circle mb-2" 
                                 style="width: 80px; height: 80px; object-fit: cover;" 
                                 alt="{{ $favorite->model->name }}">
                            <div class="small fw-bold text-dark">{{ $favorite->model->name }}</div>
                            <div class="small text-muted">{{ $favorite->model->modelProfile->country }}</div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection
