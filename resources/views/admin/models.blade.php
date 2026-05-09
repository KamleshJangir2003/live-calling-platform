@extends('layouts.admin')

@section('title', 'Models Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="fw-bold mb-0">Models</h6>
    <a href="{{ route('admin.models.create') }}" class="btn btn-sm btn-primary">+ Add Model</a>
    <form method="GET" class="d-flex gap-2">
        <select name="kyc_status" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">All KYC Status</option>
            <option value="pending" {{ request('kyc_status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('kyc_status') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ request('kyc_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Model</th>
                    <th>Country</th>
                    <th>Audio/Video Price</th>
                    <th>Total Calls</th>
                    <th>Earnings</th>
                    <th>KYC Status</th>
                    <th>Online</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($models as $model)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $model->avatar_url }}" width="36" height="36" class="rounded-circle" style="object-fit:cover">
                                <div>
                                    <div class="fw-bold small">{{ $model->name }}</div>
                                    <small class="text-muted">{{ $model->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="small">{{ $model->modelProfile->country ?? '-' }}</td>
                        <td class="small">₹{{ $model->modelProfile->audio_price ?? 0 }} / ₹{{ $model->modelProfile->video_price ?? 0 }}</td>
                        <td>{{ $model->modelProfile->total_calls ?? 0 }}</td>
                        <td class="text-success fw-bold">₹{{ number_format($model->modelProfile->total_earnings ?? 0, 2) }}</td>
                        <td>
                            <span class="badge badge-{{ $model->modelProfile->kyc_status ?? 'pending' }}">
                                {{ ucfirst($model->modelProfile->kyc_status ?? 'pending') }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $model->modelProfile->online_status ? 'bg-success' : 'bg-secondary' }}">
                                {{ $model->modelProfile->online_status ? 'Online' : 'Offline' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @if(($model->modelProfile->kyc_status ?? '') === 'pending' && $model->modelProfile->kyc_document)
                                    <form action="{{ route('admin.models.approve-kyc', $model->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-xs btn-success" style="font-size:0.75rem;padding:2px 8px" title="Approve KYC">✓ Approve</button>
                                    </form>
                                    <form action="{{ route('admin.models.reject-kyc', $model->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-xs btn-danger" style="font-size:0.75rem;padding:2px 8px" title="Reject KYC">✗ Reject</button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.users.toggle-status', $model->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-xs {{ $model->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success' }}" style="font-size:0.75rem;padding:2px 8px">
                                        {{ $model->status === 'active' ? 'Ban' : 'Unban' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No models found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $models->appends(request()->query())->links() }}</div>
</div>
@endsection
