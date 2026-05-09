@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Stats Grid -->
<div class="row g-3 mb-4">
    @php
    $cards = [
        ['label'=>'Total Users','value'=>$stats['total_users'],'icon'=>'bi-people','color'=>'#00b894','bg'=>'#e8f8f5'],
        ['label'=>'Total Models','value'=>$stats['total_models'],'icon'=>'bi-person-badge','color'=>'#6c5ce7','bg'=>'#f0edff'],
        ['label'=>'Total Calls','value'=>$stats['total_calls'],'icon'=>'bi-telephone','color'=>'#0984e3','bg'=>'#e8f4fd'],
        ['label'=>'Today Revenue','value'=>'₹'.number_format($stats['today_revenue'],2),'icon'=>'bi-currency-rupee','color'=>'#e17055','bg'=>'#fef0ed'],
        ['label'=>'Total Revenue','value'=>'₹'.number_format($stats['total_revenue'],2),'icon'=>'bi-graph-up','color'=>'#00b894','bg'=>'#e8f8f5'],
        ['label'=>'Admin Wallet','value'=>'₹'.number_format($stats['admin_wallet'],2),'icon'=>'bi-wallet2','color'=>'#6c5ce7','bg'=>'#f0edff'],
        ['label'=>'Total Commission','value'=>'₹'.number_format($stats['total_commission'],2),'icon'=>'bi-percent','color'=>'#00b894','bg'=>'#e8f8f5'],
        ['label'=>'Pending KYC','value'=>$stats['pending_kyc'],'icon'=>'bi-person-check','color'=>'#fdcb6e','bg'=>'#fef9e7'],
        ['label'=>'Pending Withdrawals','value'=>$stats['pending_withdrawals'],'icon'=>'bi-cash-stack','color'=>'#d63031','bg'=>'#fdecea'],
    ];
    @endphp
    @foreach($cards as $card)
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-2 stat-inner">
                    <div class="stat-icon" style="background:{{ $card['bg'] }};color:{{ $card['color'] }}">
                        <i class="bi {{ $card['icon'] }}"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="stat-value">{{ $card['value'] }}</div>
                        <div class="stat-label">{{ $card['label'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-3">
    <!-- Recent Transactions -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
                <h6 class="fw-bold mb-0">Recent Transactions</h6>
                <a href="{{ route('admin.transactions') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>User</th><th>Type</th><th>Amount</th><th>Date</th></tr>
                        </thead>
                        <tbody>
                            @foreach($recentTransactions as $txn)
                                <tr>
                                    <td class="small">{{ $txn->user->name ?? 'N/A' }}</td>
                                    <td><span class="badge {{ $txn->type === 'recharge' ? 'bg-success' : ($txn->type === 'earning' ? 'bg-info' : 'bg-danger') }}">{{ ucfirst(str_replace('_',' ',$txn->type)) }}</span></td>
                                    <td class="fw-bold">₹{{ number_format($txn->amount, 2) }}</td>
                                    <td class="small text-muted">{{ $txn->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Calls -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
                <h6 class="fw-bold mb-0">Recent Calls</h6>
            </div>
            <div class="card-body p-0">
                @foreach($recentCalls as $call)
                    <div class="d-flex align-items-center gap-2 p-3 border-bottom">
                        <div class="flex-grow-1 min-w-0">
                            <div class="small fw-bold text-truncate">{{ $call->caller->name ?? 'N/A' }} → {{ $call->receiver->name ?? 'N/A' }}</div>
                            <small class="text-muted">{{ ucfirst($call->call_type) }} · {{ $call->duration_formatted }} · ₹{{ $call->amount }}</small>
                        </div>
                        <span class="badge flex-shrink-0 {{ $call->status === 'completed' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($call->status) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
