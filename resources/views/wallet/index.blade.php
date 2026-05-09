@extends('layouts.app')

@section('title', 'My Wallet')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card p-4 text-center" style="background:linear-gradient(135deg,#00b894,#00cec9);color:white;border-radius:16px">
            <i class="bi bi-wallet2 fs-2 mb-2"></i>
            <div class="fw-bold" style="font-size:clamp(1.4rem,5vw,2rem)">₹{{ number_format(auth()->user()->wallet_balance, 2) }}</div>
            <div class="opacity-75">Available Balance</div>
            <button class="btn btn-white mt-3 fw-bold" style="background:white;color:#00b894" data-bs-toggle="modal" data-bs-target="#walletModal">
                <i class="bi bi-plus-circle me-2"></i>Recharge Wallet
            </button>
        </div>
    </div>
    <div class="col-12 col-md-8">
        <div class="card p-3 h-100">
            <h6 class="fw-bold mb-3">Quick Recharge</h6>
            <div class="d-flex gap-2 flex-wrap">
                @foreach([100, 200, 500, 1000, 2000, 5000] as $amt)
                    <button class="btn btn-outline-primary quick-recharge" data-amount="{{ $amt }}">₹{{ $amt }}</button>
                @endforeach
            </div>
            <p class="text-muted small mt-3 mb-0"><i class="bi bi-shield-check me-1 text-success"></i>Secured by Razorpay. All transactions are encrypted.</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white border-0 pt-3">
        <h6 class="fw-bold mb-0">Transaction History</h6>
    </div>
    <div class="card-body p-0">
        @if($transactions->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-receipt fs-1"></i>
                <p class="mt-2">No transactions yet</p>
            </div>
        @else
            {{-- Desktop Table --}}
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Date</th><th>Description</th><th>Type</th><th>Amount</th><th>Balance</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $txn)
                            <tr>
                                <td class="small text-muted">{{ $txn->created_at->format('d M, H:i') }}</td>
                                <td class="small">{{ $txn->description }}</td>
                                <td>
                                    <span class="badge rounded-pill
                                        @if($txn->type === 'recharge') bg-success
                                        @elseif($txn->type === 'earning') bg-info
                                        @elseif($txn->type === 'withdrawal') bg-warning text-dark
                                        @else bg-danger @endif">
                                        {{ ucfirst(str_replace('_', ' ', $txn->type)) }}
                                    </span>
                                </td>
                                <td class="fw-bold {{ in_array($txn->type, ['recharge','earning']) ? 'text-success' : 'text-danger' }}">
                                    {{ in_array($txn->type, ['recharge','earning']) ? '+' : '-' }}₹{{ number_format($txn->amount, 2) }}
                                </td>
                                <td class="small">₹{{ number_format($txn->balance_after, 2) }}</td>
                                <td><span class="badge {{ $txn->status === 'completed' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($txn->status) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="d-md-none p-2">
                @foreach($transactions as $txn)
                    @php $isCredit = in_array($txn->type, ['recharge','earning']); @endphp
                    <div class="d-flex align-items-center gap-2 py-2 border-bottom">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:38px;height:38px;background:{{ $isCredit ? '#e8f8f5' : '#fdecea' }}">
                            <i class="bi {{ $isCredit ? 'bi-arrow-down-circle-fill text-success' : 'bi-arrow-up-circle-fill text-danger' }}"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="small fw-semibold text-truncate">{{ $txn->description }}</div>
                            <small class="text-muted">{{ $txn->created_at->format('d M, H:i') }}</small>
                        </div>
                        <div class="text-end flex-shrink-0">
                            <div class="fw-bold {{ $isCredit ? 'text-success' : 'text-danger' }}">{{ $isCredit ? '+' : '-' }}₹{{ number_format($txn->amount, 2) }}</div>
                            <small class="text-muted">Bal: ₹{{ number_format($txn->balance_after, 2) }}</small>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="p-3">{{ $transactions->links() }}</div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.quick-recharge').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('rechargeAmount').value = this.dataset.amount;
            new bootstrap.Modal(document.getElementById('walletModal')).show();
        });
    });
</script>
@endpush
