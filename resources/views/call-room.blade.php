@extends('layouts.app')

@section('title', 'Call Room')

@push('styles')
<style>
    body { background: #1a1a2e !important; padding-bottom: 0 !important; }
    .call-room { min-height: calc(100vh - 70px); display: flex; flex-direction: column; }
    #remoteVideo, #localVideo { background: #2d3436; border-radius: 12px; }
    #remoteVideo { width: 100%; height: 60vh; object-fit: cover; }
    #localVideo { width: 120px; height: 160px; object-fit: cover; position: absolute; bottom: 100px; right: 20px; border: 2px solid #00b894; }
    .call-controls { background: rgba(0,0,0,0.7); border-radius: 20px; padding: 12px 20px; display: flex; gap: 16px; justify-content: center; }
    .ctrl-btn { width: 52px; height: 52px; border-radius: 50%; border: none; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; cursor: pointer; transition: transform 0.2s; }
    .ctrl-btn:hover { transform: scale(1.1); }
    .call-info { color: white; text-align: center; padding: 16px; }
    .call-timer { font-size: 1.5rem; font-weight: 700; color: #00b894; }
    .balance-bar { background: rgba(0,184,148,0.2); border-radius: 8px; padding: 8px 16px; color: #00b894; font-size: 0.85rem; }
</style>
@endpush

@section('content')
<div class="call-room position-relative">
    <div class="call-info">
        <h5 class="text-white mb-1" id="otherUserName">
            @if($call->caller_id === auth()->id())
                {{ $call->receiver->name }}
            @else
                {{ $call->caller->name }}
            @endif
        </h5>
        <div class="call-timer" id="callTimer">00:00</div>
        <div class="balance-bar d-inline-block mt-1">
            <i class="bi bi-wallet2 me-1"></i>Balance: ₹<span id="currentBalance">{{ auth()->user()->wallet_balance }}</span>
        </div>
    </div>

    <div class="position-relative">
        @if($call->call_type === 'video')
            <video id="remoteVideo" autoplay playsinline></video>
            <video id="localVideo" autoplay playsinline muted></video>
        @else
            <div id="remoteVideo" class="d-flex align-items-center justify-content-center" style="height:40vh">
                <div class="text-center text-white">
                    <i class="bi bi-person-circle" style="font-size:5rem;opacity:0.5"></i>
                    <p class="mt-2 opacity-75">Audio Call in Progress</p>
                    <div class="d-flex gap-1 justify-content-center mt-2" id="audioWave">
                        @for($i=0;$i<5;$i++)<div style="width:4px;height:20px;background:#00b894;border-radius:2px;animation:wave 1s ease-in-out infinite;animation-delay:{{ $i*0.1 }}s"></div>@endfor
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="d-flex justify-content-center mt-3">
        <div class="call-controls">
            <button class="ctrl-btn" id="toggleMic" style="background:#2d3436;color:white" title="Mute">
                <i class="bi bi-mic-fill"></i>
            </button>
            @if($call->call_type === 'video')
            <button class="ctrl-btn" id="toggleCamera" style="background:#2d3436;color:white" title="Camera">
                <i class="bi bi-camera-video-fill"></i>
            </button>
            @endif
            <button class="ctrl-btn" id="endCallBtn" style="background:#d63031;color:white" title="End Call">
                <i class="bi bi-telephone-x-fill"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.20.0.js"></script>
<style>
    @keyframes wave { 0%,100%{height:8px} 50%{height:24px} }
    .navbar, .bottom-nav { display: none !important; }
    main { padding: 0 !important; }
</style>
<script>
    const callId = {{ $call->id }};
    const callType = '{{ $call->call_type }}';
    const appId = '{{ config("services.agora.app_id") }}';
    const channelName = '{{ $call->channel_name }}';
    const token = '{{ $call->agora_token }}' || null;
    const uid = {{ auth()->id() }};
    const pricePerMinute = {{ $call->price_per_minute }};
    const isCaller = {{ $call->caller_id === auth()->id() ? 'true' : 'false' }};

    let client, localAudioTrack, localVideoTrack;
    let callSeconds = 0, timerInterval, balanceCheckInterval;
    let micMuted = false, camOff = false;

    async function initCall() {
        client = AgoraRTC.createClient({ mode: 'rtc', codec: 'vp8' });

        client.on('user-published', async (user, mediaType) => {
            await client.subscribe(user, mediaType);
            if (mediaType === 'video' && callType === 'video') {
                user.videoTrack.play('remoteVideo');
            }
            if (mediaType === 'audio') {
                user.audioTrack.play();
            }
        });

        await client.join(appId, channelName, token, uid);

        localAudioTrack = await AgoraRTC.createMicrophoneAudioTrack();
        const tracks = [localAudioTrack];

        if (callType === 'video') {
            localVideoTrack = await AgoraRTC.createCameraVideoTrack();
            localVideoTrack.play('localVideo');
            tracks.push(localVideoTrack);
        }

        await client.publish(tracks);

        // Start timer
        timerInterval = setInterval(() => {
            callSeconds++;
            const m = String(Math.floor(callSeconds / 60)).padStart(2, '0');
            const s = String(callSeconds % 60).padStart(2, '0');
            document.getElementById('callTimer').textContent = `${m}:${s}`;

            // Update balance display every minute
            if (callSeconds % 60 === 0) {
                const currentBal = parseFloat(document.getElementById('currentBalance').textContent);
                const newBal = Math.max(0, currentBal - pricePerMinute);
                document.getElementById('currentBalance').textContent = newBal.toFixed(2);

                if (newBal < pricePerMinute) {
                    endCall();
                }
            }
        }, 1000);
    }

    async function endCall() {
        clearInterval(timerInterval);
        if (localAudioTrack) localAudioTrack.close();
        if (localVideoTrack) localVideoTrack.close();
        if (client) await client.leave();

        await fetch(`/call/${callId}/end`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json' },
            body: JSON.stringify({ duration: callSeconds })
        });

        window.location.href = '/call/history';
    }

    document.getElementById('endCallBtn').addEventListener('click', endCall);

    document.getElementById('toggleMic').addEventListener('click', function() {
        micMuted = !micMuted;
        if (localAudioTrack) localAudioTrack.setEnabled(!micMuted);
        this.style.background = micMuted ? '#e17055' : '#2d3436';
        this.querySelector('i').className = micMuted ? 'bi bi-mic-mute-fill' : 'bi bi-mic-fill';
    });

    const camBtn = document.getElementById('toggleCamera');
    if (camBtn) {
        camBtn.addEventListener('click', function() {
            camOff = !camOff;
            if (localVideoTrack) localVideoTrack.setEnabled(!camOff);
            this.style.background = camOff ? '#e17055' : '#2d3436';
            this.querySelector('i').className = camOff ? 'bi bi-camera-video-off-fill' : 'bi bi-camera-video-fill';
        });
    }

    // Listen for call end from other side
    const callChannel = pusher.subscribe(`private-user.${uid}`);
    callChannel.bind('call.status', function(data) {
        if (data.call_id == callId && (data.status === 'completed' || data.status === 'rejected')) {
            endCall();
        }
    });

    initCall().catch(console.error);
</script>
@endpush
