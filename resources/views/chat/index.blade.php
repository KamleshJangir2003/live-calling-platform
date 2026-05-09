@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<h5 class="fw-bold mb-3">Messages</h5>

@if($conversations->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-chat-dots fs-1 text-muted"></i>
        <p class="text-muted mt-2">No conversations yet. Start chatting with a model!</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Browse Models</a>
    </div>
@else
    <div class="card">
        @foreach($conversations as $userId => $msg)
            @php $other = $msg->sender_id === auth()->id() ? $msg->receiver : $msg->sender; @endphp
            <a href="{{ route('chat.conversation', $other->id) }}" class="text-decoration-none">
                <div class="d-flex align-items-center gap-3 p-3 border-bottom hover-bg">
                    <img src="{{ $other->avatar_url }}" width="48" height="48" class="rounded-circle" style="object-fit:cover">
                    <div class="flex-grow-1 min-w-0">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold text-dark">{{ $other->name }}</span>
                            <small class="text-muted">{{ $msg->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="text-muted small mb-0 text-truncate">
                            {{ $msg->sender_id === auth()->id() ? 'You: ' : '' }}{{ $msg->message }}
                        </p>
                    </div>
                    @if(!$msg->is_read && $msg->receiver_id === auth()->id())
                        <span class="badge rounded-pill bg-primary">New</span>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
@endif
@endsection
