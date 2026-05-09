@extends('layouts.admin')

@section('title', 'Withdrawal Requests')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="fw-bold mb-0">Withdrawal Requests</h6>
    <form method="GET">
        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
    </form>
</div>

<div class="card">
    {{-- Desktop --}}
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Model</th><th>Amount</th><th>Payment Details</th><th>Status</th><th>Requested</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($withdrawals as $wd)
                    <tr>
                        <td>
                            <div class="fw-bold small">{{ $wd->user->name }}</div>
                            <small class="text-muted">{{ $wd->user->email }}</small>
                        </td>
                        <td class="fw-bold fs-6">₹{{ number_format($wd->amount, 2) }}</td>
                        <td class="small">
                            @if($wd->upi_id) <div>UPI: {{ $wd->upi_id }}</div> @endif
                            @if($wd->bank_name) <div>{{ $wd->bank_name }} - {{ $wd->account_number }}</div> @endif
                            @if($wd->ifsc_code) <div>IFSC: {{ $wd->ifsc_code }}</div> @endif
                        </td>
                        <td>
                            <span class="badge @if($wd->status==='paid') bg-success @elseif($wd->status==='pending') bg-warning text-dark @else bg-danger @endif">
                                {{ ucfirst($wd->status) }}
                            </span>
                        </td>
                        <td class="small text-muted">{{ $wd->created_at->format('d M Y') }}</td>
                        <td>
                            @if($wd->status === 'pending')
                                <div class="d-flex gap-1">
                                    <form action="{{ route('admin.withdrawals.approve', $wd->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success">✓</button>
                                    </form>
                                    <form action="{{ route('admin.withdrawals.reject', $wd->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-danger">✗</button>
                                    </form>
                                </div>
                            @else
                                <small class="text-muted">{{ $wd->admin_note }}</small>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">No withdrawal requests</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile --}}
    <div class="d-md-none p-2">
        @forelse($withdrawals as $wd)
            <div class="card mb-2 p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="min-w-0 me-2">
                        <div class="fw-bold text-truncate">{{ $wd->user->name }}</div>
                        <small class="text-muted d-block text-truncate">{{ $wd->user->email }}</small>
                    </div>
                    <div class="text-end flex-shrink-0">
                        <div class="fw-bold fs-6">₹{{ number_format($wd->amount, 2) }}</div>
                        <span class="badge @if($wd->status==='paid') bg-success @elseif($wd->status==='pending') bg-warning text-dark @else bg-danger @endif">{{ ucfirst($wd->status) }}</span>
                    </div>
                </div>
                <div class="small text-muted mb-2">
                    @if($wd->upi_id) <span class="me-2">UPI: {{ $wd->upi_id }}</span> @endif
                    @if($wd->bank_name) <span>{{ $wd->bank_name }}</span> @endif
                    <span class="ms-2">{{ $wd->created_at->format('d M Y') }}</span>
                </div>
                @if($wd->status === 'pending')
                    <div class="d-flex gap-2">
                        <form action="{{ route('admin.withdrawals.approve', $wd->id) }}" method="POST" class="flex-fill">
                            @csrf
                            <button class="btn btn-sm btn-success w-100">✓ Approve</button>
                        </form>
                        <form action="{{ route('admin.withdrawals.reject', $wd->id) }}" method="POST" class="flex-fill">
                            @csrf
                            <button class="btn btn-sm btn-danger w-100">✗ Reject</button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-4 text-muted">No withdrawal requests</div>
        @endforelse
    </div>

    <div class="p-3">{{ $withdrawals->links() }}</div>
</div>
@endsection
