@extends('layouts.app')

@section('title', 'Earnings & Withdrawals')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card p-4 text-center" style="background:linear-gradient(135deg,#00b894,#00cec9);color:white;border-radius:16px">
            <i class="bi bi-cash-stack fs-2 mb-2"></i>
            <div class="fw-bold" style="font-size:clamp(1.4rem,5vw,2rem)">₹{{ number_format(auth()->user()->wallet_balance, 2) }}</div>
            <div class="opacity-75">Available to Withdraw</div>
        </div>
    </div>
    <div class="col-12 col-md-8">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">Request Withdrawal</h6>
            <form action="{{ route('model.withdrawal.request') }}" method="POST">
                @csrf
                <div class="row g-2">
                    <div class="col-6 col-md-6">
                        <input type="number" name="amount" class="form-control" placeholder="Amount (min ₹100)" min="100" required>
                    </div>
                    <div class="col-6 col-md-6">
                        <input type="text" name="upi_id" class="form-control" placeholder="UPI ID">
                    </div>
                    <div class="col-12 col-md-4">
                        <input type="text" name="bank_name" class="form-control" placeholder="Bank Name">
                    </div>
                    <div class="col-6 col-md-4">
                        <input type="text" name="account_number" class="form-control" placeholder="Account No.">
                    </div>
                    <div class="col-6 col-md-4">
                        <input type="text" name="ifsc_code" class="form-control" placeholder="IFSC Code">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success fw-bold w-100 w-md-auto">
                            <i class="bi bi-send me-2"></i>Submit Withdrawal Request
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-md-7">
        <div class="card">
            <div class="card-header bg-white border-0 pt-3"><h6 class="fw-bold mb-0">Earning History</h6></div>
            <div class="card-body p-0">
                @if($transactions->isEmpty())
                    <div class="text-center py-4 text-muted">No earnings yet</div>
                @else
                    {{-- Desktop --}}
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr><th>Date</th><th>Description</th><th>Amount</th></tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $txn)
                                    <tr>
                                        <td class="small text-muted">{{ $txn->created_at->format('d M, H:i') }}</td>
                                        <td class="small">{{ $txn->description }}</td>
                                        <td class="text-success fw-bold">+₹{{ number_format($txn->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- Mobile --}}
                    <div class="d-md-none p-2">
                        @foreach($transactions as $txn)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div class="min-w-0 me-2">
                                    <div class="small text-truncate">{{ $txn->description }}</div>
                                    <small class="text-muted">{{ $txn->created_at->format('d M, H:i') }}</small>
                                </div>
                                <span class="text-success fw-bold flex-shrink-0">+₹{{ number_format($txn->amount, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="p-3">{{ $transactions->links() }}</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 col-md-5">
        <div class="card">
            <div class="card-header bg-white border-0 pt-3"><h6 class="fw-bold mb-0">Withdrawal Requests</h6></div>
            <div class="card-body p-0">
                @if($withdrawals->isEmpty())
                    <div class="text-center py-4 text-muted">No withdrawal requests</div>
                @else
                    @foreach($withdrawals as $wd)
                        <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                            <div>
                                <div class="fw-bold">₹{{ number_format($wd->amount, 2) }}</div>
                                <small class="text-muted">{{ $wd->created_at->format('d M Y') }}</small>
                            </div>
                            <span class="badge
                                @if($wd->status === 'paid') bg-success
                                @elseif($wd->status === 'pending') bg-warning text-dark
                                @else bg-danger @endif">
                                {{ ucfirst($wd->status) }}
                            </span>
                        </div>
                    @endforeach
                    <div class="p-3">{{ $withdrawals->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
