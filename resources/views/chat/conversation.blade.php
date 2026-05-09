@extends('layouts.app')

@section('title', 'Chat with ' . $otherUser->name)

@push('styles')
<style>
    .chat-container { height: calc(100vh - 200px); display: flex; flex-direction: column; }
    .chat-messages { flex: 1; overflow-y: auto; padding: 16px; display: flex; flex-direction: column; gap: 8px; }
    .message-bubble { max-width: 70%; padding: 10px 14px; border-radius: 16px; font-size: 0.9rem; }
    .message-sent { background: #00b894; color: white; border-bottom-right-radius: 4px; align-self: flex-end; }
    .message-received { background: white; color: #2d3436; border-bottom-left-radius: 4px; align-self: flex-start; box-shadow: 0 1px 4px rgba(0,0,0,0.08); }
    .chat-input-bar { background: white; border-top: 1px solid #e0f5f0; padding: 12px; }
</style>
@endpush

@section('content')
<div class="card chat-container">
    <div class="card-header bg-white d-flex align-items-center gap-3 py-2">
        <a href="{{ route('chat') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
        <img src="{{ $otherUser->avatar_url }}" width="40" height="40" class="rounded-circle" style="object-fit:cover">
        <div>
            <div class="fw-bold">{{ $otherUser->name }}</div>
            <small class="text-muted" id="onlineStatus">
                @if($otherUser->modelProfile && $otherUser->modelProfile->online_status)
                    <span class="text-success">● Online</span>
                @else
                    <span class="text-muted">○ Offline</span>
                @endif
            </small>
        </div>
        @if($otherUser->isModel())
            <div class="ms-auto d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary call-btn" data-model-id="{{ $otherUser->id }}" data-call-type="audio" data-model-name="{{ $otherUser->name }}" data-price="{{ $otherUser->modelProfile->audio_price ?? 0 }}">
                    <i class="bi bi-mic-fill"></i>
                </button>
                <button class="btn btn-sm btn-primary call-btn" data-model-id="{{ $otherUser->id }}" data-call-type="video" data-model-name="{{ $otherUser->name }}" data-price="{{ $otherUser->modelProfile->video_price ?? 0 }}">
                    <i class="bi bi-camera-video-fill"></i>
                </button>
            </div>
        @endif
    </div>

    <div class="chat-messages" id="chatMessages">
        @foreach($messages as $msg)
            <div class="message-bubble {{ $msg->sender_id === auth()->id() ? 'message-sent' : 'message-received' }}">
                {{ $msg->message }}
                <div class="text-end mt-1" style="font-size:0.7rem;opacity:0.7">{{ $msg->created_at->format('H:i') }}</div>
            </div>
        @endforeach
    </div>

    <div class="chat-input-bar">
        <div class="input-group">
            <input type="text" id="messageInput" class="form-control" placeholder="Type a message..." maxlength="1000">
            <button class="btn btn-primary" id="sendBtn">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const receiverId = {{ $otherUser->id }};
    const chatMessages = document.getElementById('chatMessages');

    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    scrollToBottom();

    function appendMessage(text, isSent, time) {
        const div = document.createElement('div');
        div.className = `message-bubble ${isSent ? 'message-sent' : 'message-received'}`;
        div.innerHTML = `${text}<div class="text-end mt-1" style="font-size:0.7rem;opacity:0.7">${time}</div>`;
        chatMessages.appendChild(div);
        scrollToBottom();
    }

    document.getElementById('sendBtn').addEventListener('click', sendMessage);
    document.getElementById('messageInput').addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });

    function sendMessage() {
        const input = document.getElementById('messageInput');
        const text = input.value.trim();
        if (!text) return;

        input.value = '';
        fetch('/chat/send', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json' },
            body: JSON.stringify({ receiver_id: receiverId, message: text })
        }).then(r => r.json()).then(data => {
            appendMessage(data.message, true, data.created_at);
        });
    }

    // Pusher real-time
    const chatChannel = pusher.subscribe(`private-chat.{{ auth()->id() }}`);
    chatChannel.bind('message.sent', function(data) {
        if (data.sender_id == receiverId) {
            appendMessage(data.message, false, data.created_at);
        }
    });
</script>
@endpush
