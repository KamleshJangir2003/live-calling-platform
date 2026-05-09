@extends('layouts.admin')

@section('title', 'Users Management')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h6 class="fw-bold mb-0">Users</h6>
    <form method="GET" class="d-flex gap-2 flex-wrap">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}" style="min-width:140px">
        <select name="status" class="form-select form-select-sm" style="min-width:110px">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Banned</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
    </form>
</div>

<div class="card">
    {{-- Desktop --}}
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>User</th><th>Phone</th><th>Wallet</th><th>Calls</th><th>Status</th><th>Joined</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $user->avatar_url }}" width="36" height="36" class="rounded-circle" style="object-fit:cover">
                                <div>
                                    <div class="fw-bold small">{{ $user->name }}</div>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="small">{{ $user->phone ?? '-' }}</td>
                        <td class="fw-bold text-success">₹{{ number_format($user->wallet_balance, 2) }}</td>
                        <td>{{ $user->callsMade()->count() }}</td>
                        <td><span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($user->status) }}</span></td>
                        <td class="small text-muted">{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm {{ $user->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                    {{ $user->status === 'active' ? 'Ban' : 'Unban' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No users found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile --}}
    <div class="d-md-none p-2">
        @forelse($users as $user)
            <div class="d-flex align-items-center gap-2 py-2 border-bottom">
                <img src="{{ $user->avatar_url }}" width="42" height="42" class="rounded-circle flex-shrink-0" style="object-fit:cover">
                <div class="flex-grow-1 min-w-0">
                    <div class="fw-bold small text-truncate">{{ $user->name }}</div>
                    <small class="text-muted d-block text-truncate">{{ $user->email }}</small>
                    <div class="d-flex gap-2 mt-1 align-items-center flex-wrap">
                        <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}" style="font-size:.65rem">{{ ucfirst($user->status) }}</span>
                        <small class="text-success fw-bold">₹{{ number_format($user->wallet_balance, 2) }}</small>
                    </div>
                </div>
                <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="flex-shrink-0">
                    @csrf
                    <button class="btn btn-sm {{ $user->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success' }}">
                        {{ $user->status === 'active' ? 'Ban' : 'Unban' }}
                    </button>
                </form>
            </div>
        @empty
            <div class="text-center py-4 text-muted">No users found</div>
        @endforelse
    </div>

    <div class="p-3">{{ $users->links() }}</div>
</div>
@endsection
