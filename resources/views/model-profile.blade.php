@extends('layouts.app')

@section('title', $model->name . ' - Profile')

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card p-4 text-center">
            <div class="position-relative d-inline-block mx-auto mb-3">
                <img src="{{ $model->modelProfile->profile_photo_url }}" width="120" height="120" class="rounded-circle" style="object-fit:cover;border:3px solid #00b894">
                <span class="position-absolute bottom-0 end-0 {{ $model->modelProfile->online_status ? 'badge-online' : 'badge-offline' }}">
                    {{ $model->modelProfile->online_status ? '● Online' : '○ Offline' }}
                </span>
            </div>
            <h5 class="fw-bold mb-1">{{ $model->name }}</h5>
            <p class="text-muted small mb-3">
                <i class="bi bi-geo-alt me-1"></i>{{ $model->modelProfile->country ?? 'Global' }}
            </p>
            @if($model->modelProfile->languages)
                <div class="d-flex flex-wrap gap-1 justify-content-center mb-3">
                    @foreach($model->modelProfile->languages_array as $lang)
                        <span class="badge bg-light text-dark border">{{ trim($lang) }}</span>
                    @endforeach
                </div>
            @endif
            <div class="d-flex gap-2 justify-content-center mb-3">
                <div class="text-center">
                    <div class="fw-bold">{{ $model->modelProfile->total_calls }}</div>
                    <small class="text-muted">Calls</small>
                </div>
                <div class="vr"></div>
                <div class="text-center">
                    <div class="fw-bold">{{ number_format($model->modelProfile->rating, 1) }}</div>
                    <small class="text-muted">Rating</small>
                </div>
            </div>
            @auth
                <button class="btn btn-outline-danger btn-sm mb-2 favorite-btn" data-model-id="{{ $model->id }}" data-favorited="{{ $isFavorite ? '1' : '0' }}">
                    <i class="bi {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }} me-1"></i>
                    {{ $isFavorite ? 'Unfavorite' : 'Add to Favorites' }}
                </button>
                <a href="{{ route('chat.conversation', $model->id) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-chat-dots me-1"></i>Send Message
                </a>
            @endauth
        </div>
    </div>

    <div class="col-md-8">
        @if($model->modelProfile->bio)
            <div class="card p-4 mb-3">
                <h6 class="fw-bold mb-2">About</h6>
                <p class="text-muted mb-0">{{ $model->modelProfile->bio }}</p>
            </div>
        @endif

        <div class="card p-4 mb-3">
            <h6 class="fw-bold mb-3">Call Pricing</h6>
            <div class="row g-3">
                @if($model->modelProfile->audio_price > 0)
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 text-center">
                            <i class="bi bi-mic-fill fs-2 text-primary mb-2 d-block"></i>
                            <div class="fw-bold fs-5">₹{{ $model->modelProfile->audio_price }}/min</div>
                            <div class="text-muted small mb-3">Audio Call</div>
                            @auth
                                <button class="btn btn-outline-primary w-100 call-btn"
                                    data-model-id="{{ $model->id }}"
                                    data-model-name="{{ $model->name }}"
                                    data-call-type="audio"
                                    data-price="{{ $model->modelProfile->audio_price }}">
                                    <i class="bi bi-telephone-fill me-2"></i>Audio Call
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">Login to Call</a>
                            @endauth
                        </div>
                    </div>
                @endif
                @if($model->modelProfile->video_price > 0)
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 text-center" style="border-color:#fdcb6e !important">
                            <i class="bi bi-camera-video-fill fs-2 text-warning mb-2 d-block"></i>
                            <div class="fw-bold fs-5">₹{{ $model->modelProfile->video_price }}/min</div>
                            <div class="text-muted small mb-3">Video Call</div>
                            @auth
                                <button class="btn btn-warning w-100 call-btn"
                                    data-model-id="{{ $model->id }}"
                                    data-model-name="{{ $model->name }}"
                                    data-call-type="video"
                                    data-price="{{ $model->modelProfile->video_price }}">
                                    <i class="bi bi-camera-video-fill me-2"></i>Video Call
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-warning w-100">Login to Call</a>
                            @endauth
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @auth
            @if($recentCalls->isNotEmpty())
                <div class="card p-4">
                    <h6 class="fw-bold mb-3">Your Recent Calls</h6>
                    @foreach($recentCalls as $call)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="badge {{ $call->call_type === 'video' ? 'bg-warning text-dark' : 'bg-primary' }}">{{ ucfirst($call->call_type) }}</span>
                            <span class="small text-muted">{{ $call->duration_formatted }}</span>
                            <span class="small text-danger fw-bold">-₹{{ $call->amount }}</span>
                            <span class="small text-muted">{{ $call->created_at->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        @endauth
    </div>
</div>

<!-- Call Pricing Modal (reused from home) -->
<div class="modal fade" id="callModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="callModalTitle">Start Call</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="bg-light rounded-3 p-3 mb-3">
                    <div class="row text-center">
                        <div class="col"><div class="fw-bold fs-5" id="modalPrice">₹0</div><small class="text-muted">Per Minute</small></div>
                        <div class="col"><div class="fw-bold fs-5" id="modalBalance">₹0</div><small class="text-muted">Your Balance</small></div>
                        <div class="col"><div class="fw-bold fs-5" id="modalMinutes">0</div><small class="text-muted">Minutes Available</small></div>
                    </div>
                </div>
                <div id="lowBalanceWarning" class="alert alert-warning d-none">
                    <i class="bi bi-exclamation-triangle me-2"></i>Insufficient balance.
                    <a href="#" data-bs-toggle="modal" data-bs-target="#walletModal" data-bs-dismiss="modal">Recharge now</a>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button class="btn btn-secondary flex-fill" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary flex-fill fw-bold" id="confirmCallBtn">
                    <i class="bi bi-telephone-fill me-2"></i>Call Now
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let selectedModelId = null, selectedCallType = null;

    document.querySelectorAll('.call-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            selectedModelId = this.dataset.modelId;
            selectedCallType = this.dataset.callType;
            const price = parseFloat(this.dataset.price);
            const balance = {{ auth()->check() ? auth()->user()->wallet_balance : 0 }};
            const minutes = price > 0 ? Math.floor(balance / price) : 0;

            document.getElementById('callModalTitle').textContent = `${this.dataset.callType === 'video' ? 'Video' : 'Audio'} Call with ${this.dataset.modelName}`;
            document.getElementById('modalPrice').textContent = `₹${price}`;
            document.getElementById('modalBalance').textContent = `₹${balance.toFixed(2)}`;
            document.getElementById('modalMinutes').textContent = minutes;

            const canCall = balance >= price;
            document.getElementById('confirmCallBtn').disabled = !canCall;
            document.getElementById('lowBalanceWarning').classList.toggle('d-none', canCall);
            new bootstrap.Modal(document.getElementById('callModal')).show();
        });
    });

    document.getElementById('confirmCallBtn').addEventListener('click', function() {
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Connecting...';
        fetch('/call/initiate', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json' },
            body: JSON.stringify({ receiver_id: selectedModelId, call_type: selectedCallType })
        }).then(r => r.json()).then(data => {
            if (data.error) { alert(data.error); this.disabled = false; this.innerHTML = '<i class="bi bi-telephone-fill me-2"></i>Call Now'; return; }
            bootstrap.Modal.getInstance(document.getElementById('callModal')).hide();
            window.location.href = `/call/${data.call_id}/room`;
        });
    });

    const favBtn = document.querySelector('.favorite-btn');
    if (favBtn) {
        favBtn.addEventListener('click', function() {
            fetch(`/favorites/${this.dataset.modelId}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).then(r => r.json()).then(data => {
                const icon = this.querySelector('i');
                icon.className = data.favorited ? 'bi bi-heart-fill me-1' : 'bi bi-heart me-1';
                this.innerHTML = (data.favorited ? '<i class="bi bi-heart-fill me-1"></i>Unfavorite' : '<i class="bi bi-heart me-1"></i>Add to Favorites');
            });
        });
    }
</script>
@endpush
