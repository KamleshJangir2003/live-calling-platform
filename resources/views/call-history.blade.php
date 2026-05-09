@extends('layouts.app')

@section('title', 'Call History')

@section('content')
<h5 class="fw-bold mb-4">Call History</h5>

@if($calls->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-telephone-x fs-1 text-muted"></i>
        <p class="text-muted mt-2">No calls yet. Start calling models!</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Browse Models</a>
    </div>
@else
    {{-- Desktop Table --}}
    <div class="card d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>With</th><th>Type</th><th>Duration</th><th>Amount</th><th>Status</th><th>Date</th></tr>
                </thead>
                <tbody>
                    @foreach($calls as $call)
                        @php $other = $call->caller_id === auth()->id() ? $call->receiver : $call->caller; @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $other->avatar_url }}" width="36" height="36" class="rounded-circle" style="object-fit:cover">
                                    <div>
                                        <div class="fw-bold small">{{ $other->name }}</div>
                                        <small class="text-muted">{{ $call->caller_id === auth()->id() ? 'Outgoing' : 'Incoming' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge {{ $call->call_type === 'video' ? 'bg-warning text-dark' : 'bg-primary' }}">{{ ucfirst($call->call_type) }}</span></td>
                            <td>{{ $call->duration_formatted }}</td>
                            <td class="fw-bold {{ $call->caller_id === auth()->id() ? 'text-danger' : 'text-success' }}">
                                {{ $call->caller_id === auth()->id() ? '-' : '+' }}₹{{ number_format($call->amount, 2) }}
                            </td>
                            <td>
                                <span class="badge @if($call->status==='completed') bg-success @elseif($call->status==='missed') bg-warning text-dark @elseif($call->status==='rejected') bg-danger @else bg-secondary @endif">
                                    {{ ucfirst($call->status) }}
                                </span>
                            </td>
                            <td class="small text-muted">{{ $call->created_at->format('d M, H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $calls->links() }}</div>
    </div>

    {{-- Mobile Cards --}}
    <div class="d-md-none">
        @foreach($calls as $call)
            @php $other = $call->caller_id === auth()->id() ? $call->receiver : $call->caller; $isOut = $call->caller_id === auth()->id(); @endphp
            <div class="card mb-2 p-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <img src="{{ $other->avatar_url }}" width="40" height="40" class="rounded-circle flex-shrink-0" style="object-fit:cover">
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-bold text-truncate">{{ $other->name }}</div>
                        <small class="text-muted">{{ $isOut ? 'Outgoing' : 'Incoming' }} · {{ $call->created_at->format('d M, H:i') }}</small>
                    </div>
                    <span class="fw-bold flex-shrink-0 {{ $isOut ? 'text-danger' : 'text-success' }}">{{ $isOut ? '-' : '+' }}₹{{ number_format($call->amount, 2) }}</span>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <span class="badge {{ $call->call_type === 'video' ? 'bg-warning text-dark' : 'bg-primary' }}">{{ ucfirst($call->call_type) }}</span>
                    <span class="badge bg-light text-dark border"><i class="bi bi-clock me-1"></i>{{ $call->duration_formatted }}</span>
                    <span class="badge @if($call->status==='completed') bg-success @elseif($call->status==='missed') bg-warning text-dark @elseif($call->status==='rejected') bg-danger @else bg-secondary @endif">{{ ucfirst($call->status) }}</span>
                </div>
            </div>
        @endforeach
        <div class="mt-2">{{ $calls->links() }}</div>
    </div>
@endif
@endsection
