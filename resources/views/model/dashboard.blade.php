@extends('layouts.app')

@section('title', 'Model Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">Welcome, {{ auth()->user()->name }}</h5>
        <small class="text-muted">Model Dashboard</small>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="badge {{ $profile->online_status ? 'bg-success' : 'bg-secondary' }} fs-6">
            {{ $profile->online_status ? '● Online' : '○ Offline' }}
        </span>
        <button class="btn btn-sm {{ $profile->online_status ? 'btn-outline-danger' : 'btn-success' }}" id="toggleOnlineBtn">
            {{ $profile->online_status ? 'Go Offline' : 'Go Online' }}
        </button>
    </div>
</div>

@if($profile->kyc_status !== 'approved')
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        @if($profile->kyc_status === 'pending' && !$profile->kyc_document)
            Your KYC is not submitted. <a href="{{ route('model.profile.edit') }}">Upload documents</a> to start receiving calls.
        @elseif($profile->kyc_status === 'pending')
            Your KYC is under review. You'll be notified once approved.
        @elseif($profile->kyc_status === 'rejected')
            Your KYC was rejected. <a href="{{ route('model.profile.edit') }}">Re-upload documents</a>.
        @endif
    </div>
@endif

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="fs-3 fw-bold text-primary">{{ $stats['total_calls'] }}</div>
            <small class="text-muted">Total Calls</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="fs-3 fw-bold text-success">₹{{ number_format($stats['today_earnings'], 2) }}</div>
            <small class="text-muted">Today's Earnings</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="fs-3 fw-bold text-info">₹{{ number_format($stats['total_earnings'], 2) }}</div>
            <small class="text-muted">Total Earnings</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="fs-3 fw-bold text-warning">₹{{ number_format(auth()->user()->wallet_balance, 2) }}</div>
            <small class="text-muted">Wallet Balance</small>
        </div>
    </div>
</div>

<!-- Pricing & Quick Actions -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card p-3">
            <h6 class="fw-bold mb-3">My Pricing</h6>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span><i class="bi bi-mic-fill text-primary me-2"></i>Audio Call</span>
                <span class="fw-bold">₹{{ $profile->audio_price }}/min</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span><i class="bi bi-camera-video-fill text-warning me-2"></i>Video Call</span>
                <span class="fw-bold">₹{{ $profile->video_price }}/min</span>
            </div>
            <a href="{{ route('model.profile.edit') }}" class="btn btn-outline-primary btn-sm mt-3">
                <i class="bi bi-pencil me-1"></i>Edit Pricing
            </a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3">
            <h6 class="fw-bold mb-3">Quick Actions</h6>
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('model.profile.edit') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-person-gear me-2"></i>Edit Profile
                </a>
                <a href="{{ route('model.earnings') }}" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-cash-stack me-2"></i>View Earnings & Withdraw
                </a>
                <a href="{{ route('call.history') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-clock-history me-2"></i>Call History
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Calls -->
<div class="card">
    <div class="card-header bg-white border-0 pt-3">
        <h6 class="fw-bold mb-0">Recent Calls</h6>
    </div>
    <div class="card-body p-0">
        @if($recentCalls->isEmpty())
            <div class="text-center py-4 text-muted"><i class="bi bi-telephone-x"></i> No calls yet</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>User</th><th>Type</th><th>Duration</th><th>Earned</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        @foreach($recentCalls as $call)
                            <tr>
                                <td>{{ $call->caller->name }}</td>
                                <td><span class="badge {{ $call->call_type === 'video' ? 'bg-warning text-dark' : 'bg-primary' }}">{{ ucfirst($call->call_type) }}</span></td>
                                <td>{{ $call->duration_formatted }}</td>
                                <td class="text-success fw-bold">₹{{ number_format($call->amount * 0.8, 2) }}</td>
                                <td class="small text-muted">{{ $call->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('toggleOnlineBtn').addEventListener('click', function() {
        fetch('{{ route("model.toggle-online") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        }).then(r => r.json()).then(data => {
            location.reload();
        });
    });
</script>
@endpush
