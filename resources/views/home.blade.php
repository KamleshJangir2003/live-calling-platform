@extends('layouts.app')
@section('title', 'Browse Models')

@section('content')

{{-- Hero --}}
<div class="hero-banner rounded-4 p-4 mb-4 text-white position-relative overflow-hidden">
    <div class="hero-bg"></div>
    <div class="row align-items-center position-relative">
        <div class="col-8">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white text-success fw-bold px-3 py-1" style="font-size:.75rem">
                    <i class="bi bi-circle-fill me-1" style="font-size:.5rem"></i>
                    {{ \App\Models\ModelProfile::where('online_status',true)->count() }} Online Now
                </span>
            </div>
            <h3 class="fw-bold mb-1">Connect Live</h3>
            <p class="mb-3 opacity-75 small">Audio & Video calls with top models worldwide</p>
            @guest
                <a href="{{ route('register') }}" class="btn btn-white fw-bold px-4" style="background:white;color:#00b894;border-radius:25px">
                    <i class="bi bi-person-plus me-2"></i>Join Free
                </a>
            @else
                <div class="d-flex align-items-center gap-3">
                    <div class="text-white">
                        <div class="fw-bold fs-5">₹{{ number_format(auth()->user()->wallet_balance, 2) }}</div>
                        <small class="opacity-75">Wallet Balance</small>
                    </div>
                    <button class="btn btn-white fw-bold px-3" style="background:white;color:#00b894;border-radius:25px" data-bs-toggle="modal" data-bs-target="#walletModal">
                        <i class="bi bi-plus-circle me-1"></i>Recharge
                    </button>
                </div>
            @endguest
        </div>
        <div class="col-4 text-end">
            <i class="bi bi-camera-video-fill" style="font-size:4rem;opacity:.2"></i>
        </div>
    </div>
</div>

{{-- Filter Bar --}}
<div class="filter-card mb-4">
    <form method="GET" action="{{ route('home') }}" id="filterForm">
        <div class="row g-2 align-items-center">
            <div class="col-6 col-sm-4 col-md-2">
                <select name="country" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                    <option value="">🌍 Country</option>
                    @foreach($countries as $c)
                        <option value="{{ $c }}" {{ request('country')==$c?'selected':'' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-sm-4 col-md-2">
                <select name="call_type" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                    <option value="">📞 Type</option>
                    <option value="audio" {{ request('call_type')=='audio'?'selected':'' }}>🎙 Audio</option>
                    <option value="video" {{ request('call_type')=='video'?'selected':'' }}>📹 Video</option>
                </select>
            </div>
            <div class="col-6 col-sm-4 col-md-2">
                <select name="sort" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                    <option value="">⭐ Sort</option>
                    <option value="rating" {{ request('sort')=='rating'?'selected':'' }}>Top Rated</option>
                    <option value="calls" {{ request('sort')=='calls'?'selected':'' }}>Popular</option>
                    <option value="price_low" {{ request('sort')=='price_low'?'selected':'' }}>Price ↑</option>
                    <option value="price_high" {{ request('sort')=='price_high'?'selected':'' }}>Price ↓</option>
                </select>
            </div>
            <div class="col-6 col-sm-4 col-md-2">
                <div class="form-check form-switch ms-1 mt-1">
                    <input class="form-check-input" type="checkbox" name="online" value="1" id="onlineOnly"
                        {{ request('online')?'checked':'' }} onchange="document.getElementById('filterForm').submit()">
                    <label class="form-check-label small fw-semibold" for="onlineOnly">Online Only</label>
                </div>
            </div>
            <div class="col-12 col-sm-8 col-md-4">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Search models..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                    @if(request()->hasAny(['country','call_type','online','search','sort']))
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">✕</a>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Results Header --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <span class="fw-bold text-dark">
            @if(request('online'))<i class="bi bi-circle-fill text-success me-1" style="font-size:.6rem"></i>Online Models
            @elseif(request('country')){{ request('country') }} Models
            @else All Models @endif
        </span>
        <span class="text-muted small ms-2">({{ $models->total() }} found)</span>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-sm btn-outline-secondary active" id="gridViewBtn" onclick="setView('grid')">
            <i class="bi bi-grid-3x3-gap-fill"></i>
        </button>
        <button class="btn btn-sm btn-outline-secondary" id="listViewBtn" onclick="setView('list')">
            <i class="bi bi-list-ul"></i>
        </button>
    </div>
</div>

{{-- Models Grid --}}
@if($models->isEmpty())
    <div class="text-center py-5">
        <div class="mb-3" style="font-size:4rem">🔍</div>
        <h5 class="text-muted">No models found</h5>
        <p class="text-muted">Try adjusting your filters</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Clear Filters</a>
    </div>
@else
    <div class="row g-3" id="modelsGrid">
        @foreach($models as $model)
        <div class="col-6 col-md-4 col-lg-3 model-col">
            <div class="model-card card h-100 position-relative">
                {{-- Online Badge --}}
                <div class="position-absolute top-0 start-0 m-2 z-1">
                    @if($model->modelProfile->online_status)
                        <span class="online-dot"></span>
                    @endif
                </div>

                {{-- Favorite Button --}}
                @auth
                <button class="btn-fav position-absolute top-0 end-0 m-2 z-1 favorite-btn border-0 bg-transparent"
                    data-model-id="{{ $model->id }}"
                    data-favorited="{{ in_array($model->id, $favoriteIds) ? '1' : '0' }}">
                    <i class="bi {{ in_array($model->id, $favoriteIds) ? 'bi-heart-fill text-danger' : 'bi-heart text-white' }}" style="font-size:1.2rem;text-shadow:0 1px 3px rgba(0,0,0,.5)"></i>
                </button>
                @endauth

                {{-- Model Photo --}}
                <a href="{{ route('model.profile', $model->id) }}" class="text-decoration-none">
                    <div class="model-photo-wrap">
                        <img src="{{ $model->modelProfile->profile_photo_url }}"
                             alt="{{ $model->name }}"
                             class="model-photo"
                             loading="lazy"
                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($model->name) }}&size=300&background=00b894&color=fff&bold=true'">
                        <div class="model-photo-overlay">
                            <div class="d-flex gap-2 justify-content-center">
                                @if($model->modelProfile->audio_price > 0)
                                <span class="overlay-price"><i class="bi bi-mic-fill me-1"></i>₹{{ $model->modelProfile->audio_price }}/m</span>
                                @endif
                                @if($model->modelProfile->video_price > 0)
                                <span class="overlay-price" style="background:rgba(253,203,110,.9);color:#2d3436"><i class="bi bi-camera-video-fill me-1"></i>₹{{ $model->modelProfile->video_price }}/m</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>

                {{-- Card Body --}}
                <div class="card-body p-2 pb-2">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div class="flex-grow-1 min-w-0">
                            <h6 class="fw-bold mb-0 text-truncate" style="font-size:.9rem">{{ $model->name }}</h6>
                            <small class="text-muted">
                                <i class="bi bi-geo-alt me-1"></i>{{ $model->modelProfile->country ?? 'Global' }}
                            </small>
                        </div>
                        <div class="text-end ms-1">
                            <div class="text-warning" style="font-size:.75rem">
                                @for($s=1;$s<=5;$s++)
                                    <i class="bi {{ $s <= round($model->modelProfile->rating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                            </div>
                            <small class="text-muted" style="font-size:.7rem">{{ $model->modelProfile->rating }}</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 mb-2">
                        <small class="text-muted" style="font-size:.7rem">
                            <i class="bi bi-telephone me-1"></i>{{ number_format($model->modelProfile->total_calls) }} calls
                        </small>
                        <span class="badge {{ $model->modelProfile->online_status ? 'bg-success' : 'bg-secondary' }}" style="font-size:.6rem">
                            {{ $model->modelProfile->online_status ? 'Online' : 'Offline' }}
                        </span>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex gap-1">
                        @if($model->modelProfile->audio_price > 0)
                        <button class="btn btn-sm btn-outline-primary flex-fill call-btn py-1"
                            data-model-id="{{ $model->id }}"
                            data-model-name="{{ $model->name }}"
                            data-model-photo="{{ $model->modelProfile->profile_photo_url }}"
                            data-call-type="audio"
                            data-price="{{ $model->modelProfile->audio_price }}"
                            data-online="{{ $model->modelProfile->online_status ? '1' : '0' }}"
                            title="Audio Call ₹{{ $model->modelProfile->audio_price }}/min">
                            <i class="bi bi-mic-fill"></i>
                        </button>
                        @endif
                        @if($model->modelProfile->video_price > 0)
                        <button class="btn btn-sm btn-primary flex-fill call-btn py-1"
                            data-model-id="{{ $model->id }}"
                            data-model-name="{{ $model->name }}"
                            data-model-photo="{{ $model->modelProfile->profile_photo_url }}"
                            data-call-type="video"
                            data-price="{{ $model->modelProfile->video_price }}"
                            data-online="{{ $model->modelProfile->online_status ? '1' : '0' }}"
                            title="Video Call ₹{{ $model->modelProfile->video_price }}/min">
                            <i class="bi bi-camera-video-fill"></i>
                        </button>
                        @endif
                        <a href="{{ route('model.profile', $model->id) }}" class="btn btn-sm btn-outline-secondary py-1" title="View Profile">
                            <i class="bi bi-person"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($models->hasPages())
    <div class="mt-4 d-flex justify-content-center">
        <nav>
            <ul class="pagination pagination-sm mb-0">
                {{-- Prev --}}
                <li class="page-item {{ $models->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link px-3" href="{{ $models->onFirstPage() ? '#' : $models->previousPageUrl() }}">
                        <i class="bi bi-chevron-left"></i> Prev
                    </a>
                </li>

                {{-- Page numbers --}}
                @foreach($models->getUrlRange(max(1, $models->currentPage()-2), min($models->lastPage(), $models->currentPage()+2)) as $page => $url)
                    <li class="page-item {{ $page == $models->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                {{-- Next --}}
                <li class="page-item {{ !$models->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link px-3" href="{{ $models->hasMorePages() ? $models->nextPageUrl() : '#' }}">
                        Next <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <div class="text-center text-muted small mt-2">
        Showing {{ $models->firstItem() }}–{{ $models->lastItem() }} of {{ $models->total() }} models
    </div>
    @endif
@endif

{{-- ── Call Pricing Modal ─────────────────────────────────── --}}
<div class="modal fade" id="callModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:20px;overflow:hidden">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center pt-0 px-4">
                <img id="callModalPhoto" src="" width="80" height="80" class="rounded-circle mb-2" style="object-fit:cover;border:3px solid #00b894">
                <h6 class="fw-bold mb-0" id="callModalName"></h6>
                <small class="text-muted" id="callModalType"></small>

                <div class="row g-2 my-3">
                    <div class="col-4">
                        <div class="bg-light rounded-3 p-2">
                            <div class="fw-bold text-primary" id="modalPrice">₹0</div>
                            <div style="font-size:.65rem" class="text-muted">Per Min</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded-3 p-2">
                            <div class="fw-bold text-success" id="modalBalance">₹0</div>
                            <div style="font-size:.65rem" class="text-muted">Balance</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded-3 p-2">
                            <div class="fw-bold text-info" id="modalMinutes">0</div>
                            <div style="font-size:.65rem" class="text-muted">Minutes</div>
                        </div>
                    </div>
                </div>

                <div id="offlineWarning" class="alert alert-warning py-2 small d-none">
                    <i class="bi bi-exclamation-triangle me-1"></i>Model is currently offline
                </div>
                <div id="lowBalanceWarning" class="alert alert-danger py-2 small d-none">
                    <i class="bi bi-wallet2 me-1"></i>Insufficient balance.
                    <a href="#" data-bs-toggle="modal" data-bs-target="#walletModal" data-bs-dismiss="modal" class="fw-bold">Recharge</a>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 px-4 pb-4 gap-2">
                <button class="btn btn-light flex-fill" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary flex-fill fw-bold" id="confirmCallBtn">
                    <i class="bi bi-telephone-fill me-1"></i>Call Now
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.hero-banner { background: linear-gradient(135deg, #00b894 0%, #00cec9 100%); }
.hero-bg { position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); }
.filter-card { background:white;border-radius:14px;padding:14px 16px;box-shadow:0 2px 12px rgba(0,0,0,.06); }
.model-card { border-radius:16px;border:none;box-shadow:0 2px 12px rgba(0,184,148,.08);transition:transform .2s,box-shadow .2s;overflow:hidden; }
.model-card:hover { transform:translateY(-4px);box-shadow:0 8px 28px rgba(0,184,148,.18); }
.model-photo-wrap { position:relative;overflow:hidden;height:190px; }
.model-photo { width:100%;height:100%;object-fit:cover;transition:transform .3s; }
.model-card:hover .model-photo { transform:scale(1.05); }
.model-photo-overlay { position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(0,0,0,.7));padding:12px 8px 8px;opacity:0;transition:opacity .2s; }
.model-card:hover .model-photo-overlay { opacity:1; }
.overlay-price { background:rgba(0,184,148,.9);color:white;border-radius:20px;padding:3px 8px;font-size:.7rem;font-weight:600; }
.online-dot { display:inline-block;width:10px;height:10px;background:#00b894;border-radius:50%;border:2px solid white;box-shadow:0 0 0 2px rgba(0,184,148,.3);animation:pulse 2s infinite; }
@keyframes pulse { 0%,100%{box-shadow:0 0 0 2px rgba(0,184,148,.3)} 50%{box-shadow:0 0 0 5px rgba(0,184,148,.1)} }
.btn-fav { padding:4px;line-height:1; }
</style>
@endpush

@push('scripts')
<script>
let selectedModelId = null, selectedCallType = null;

document.querySelectorAll('.call-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        @guest window.location.href = '{{ route("login") }}'; return; @endguest

        selectedModelId = this.dataset.modelId;
        selectedCallType = this.dataset.callType;
        const price    = parseFloat(this.dataset.price);
        const balance  = {{ auth()->check() ? auth()->user()->wallet_balance : 0 }};
        const minutes  = price > 0 ? Math.floor(balance / price) : 0;
        const isOnline = this.dataset.online === '1';

        document.getElementById('callModalPhoto').src  = this.dataset.modelPhoto;
        document.getElementById('callModalName').textContent  = this.dataset.modelName;
        document.getElementById('callModalType').textContent  = (selectedCallType === 'video' ? '📹 Video' : '🎙 Audio') + ' Call';
        document.getElementById('modalPrice').textContent     = '₹' + price;
        document.getElementById('modalBalance').textContent   = '₹' + balance.toFixed(2);
        document.getElementById('modalMinutes').textContent   = minutes;

        const canCall = balance >= price;
        document.getElementById('confirmCallBtn').disabled = !canCall || !isOnline;
        document.getElementById('lowBalanceWarning').classList.toggle('d-none', canCall);
        document.getElementById('offlineWarning').classList.toggle('d-none', isOnline);

        new bootstrap.Modal(document.getElementById('callModal')).show();
    });
});

document.getElementById('confirmCallBtn').addEventListener('click', function() {
    this.disabled = true;
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Connecting...';
    fetch('/call/initiate', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json' },
        body: JSON.stringify({ receiver_id: selectedModelId, call_type: selectedCallType })
    }).then(r => r.json()).then(data => {
        if (data.error) {
            alert(data.error);
            this.disabled = false;
            this.innerHTML = '<i class="bi bi-telephone-fill me-1"></i>Call Now';
            return;
        }
        bootstrap.Modal.getInstance(document.getElementById('callModal')).hide();
        window.location.href = '/call/' + data.call_id + '/room';
    }).catch(() => {
        this.disabled = false;
        this.innerHTML = '<i class="bi bi-telephone-fill me-1"></i>Call Now';
    });
});

document.querySelectorAll('.favorite-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        fetch('/favorites/' + this.dataset.modelId, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        }).then(r => r.json()).then(data => {
            const icon = this.querySelector('i');
            icon.className = data.favorited ? 'bi bi-heart-fill text-danger' : 'bi bi-heart text-white';
        });
    });
});

function setView(type) {
    const grid = document.getElementById('modelsGrid');
    if (type === 'list') {
        grid.classList.remove('row-cols-2','row-cols-md-3','row-cols-lg-4');
        document.querySelectorAll('.model-col').forEach(c => { c.className = 'col-12 model-col'; });
        document.getElementById('listViewBtn').classList.add('active');
        document.getElementById('gridViewBtn').classList.remove('active');
    } else {
        document.querySelectorAll('.model-col').forEach(c => { c.className = 'col-6 col-md-4 col-lg-3 model-col'; });
        document.getElementById('gridViewBtn').classList.add('active');
        document.getElementById('listViewBtn').classList.remove('active');
    }
}
</script>
@endpush
