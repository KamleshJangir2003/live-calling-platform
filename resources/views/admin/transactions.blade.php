@extends('layouts.admin')

@section('title', 'Transactions')

@section('content')
<div class="card mb-3 p-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-6 col-md-3">
            <label class="form-label small fw-semibold">Type</label>
            <select name="type" class="form-select form-select-sm">
                <option value="">All Types</option>
                @foreach(['recharge','call_deduction','earning','withdrawal','refund'] as $type)
                    <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$type)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-3">
            <label class="form-label small fw-semibold">From</label>
            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
        </div>
        <div class="col-6 col-md-3">
            <label class="form-label small fw-semibold">To</label>
            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
        </div>
        <div class="col-6 col-md-3">
            <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
        </div>
    </form>
</div>

<div class="card">
    {{-- Desktop --}}
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>User</th><th>Type</th><th>Amount</th><th>Balance After</th><th>Status</th><th>Date</th></tr>
            </thead>
            <tbody>
                @forelse($transactions as $txn)
                    <tr>
                        <td class="small">{{ $txn->user->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge
                                @if($txn->type === 'recharge') bg-success
                                @elseif($txn->type === 'earning') bg-info
                                @elseif($txn->type === 'withdrawal') bg-warning text-dark
                                @else bg-danger @endif">
                                {{ ucfirst(str_replace('_',' ',$txn->type)) }}
                            </span>
                        </td>
                        <td class="fw-bold {{ in_array($txn->type,['recharge','earning']) ? 'text-success' : 'text-danger' }}">
                            {{ in_array($txn->type,['recharge','earning']) ? '+' : '-' }}₹{{ number_format($txn->amount,2) }}
                        </td>
                        <td class="small">₹{{ number_format($txn->balance_after,2) }}</td>
                        <td><span class="badge {{ $txn->status === 'completed' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($txn->status) }}</span></td>
                        <td class="small text-muted">{{ $txn->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">No transactions found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile --}}
    <div class="d-md-none p-2">
        @forelse($transactions as $txn)
            @php $isCredit = in_array($txn->type, ['recharge','earning']); @endphp
            <div class="d-flex align-items-center gap-2 py-2 border-bottom">
                <div class="flex-grow-1 min-w-0">
                    <div class="small fw-semibold text-truncate">{{ $txn->user->name ?? 'N/A' }}</div>
                    <div class="d-flex gap-1 mt-1 flex-wrap">
                        <span class="badge @if($txn->type==='recharge') bg-success @elseif($txn->type==='earning') bg-info @elseif($txn->type==='withdrawal') bg-warning text-dark @else bg-danger @endif" style="font-size:.65rem">{{ ucfirst(str_replace('_',' ',$txn->type)) }}</span>
                        <small class="text-muted">{{ $txn->created_at->format('d M, H:i') }}</small>
                    </div>
                </div>
                <div class="text-end flex-shrink-0">
                    <div class="fw-bold {{ $isCredit ? 'text-success' : 'text-danger' }}">{{ $isCredit ? '+' : '-' }}₹{{ number_format($txn->amount,2) }}</div>
                    <small class="text-muted">₹{{ number_format($txn->balance_after,2) }}</small>
                </div>
            </div>
        @empty
            <div class="text-center py-4 text-muted">No transactions found</div>
        @endforelse
    </div>

    <div class="p-3">{{ $transactions->links() }}</div>
</div>
@endsection
