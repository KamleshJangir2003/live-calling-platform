<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LiveCall') - Live Audio & Video Calls</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary: #00b894;
            --primary-dark: #00a381;
            --primary-light: #e8f8f5;
            --accent: #fd79a8;
            --dark: #2d3436;
            --nav-h: 62px;
            --bottom-h: 64px;
        }
        * { box-sizing: border-box; }
        body {
            background: #f4faf8;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            padding-top: var(--nav-h);
            padding-bottom: var(--bottom-h);
            min-height: 100vh;
        }
        /* ── Navbar ── */
        .top-navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1030;
            height: var(--nav-h);
            background: white;
            border-bottom: 1px solid #e8f5f0;
            box-shadow: 0 2px 16px rgba(0,184,148,.08);
            display: flex; align-items: center; padding: 0 16px; gap: 12px;
        }
        .nav-brand { color: var(--primary); font-weight: 800; font-size: 1.3rem; text-decoration: none; display: flex; align-items: center; gap: 6px; }
        .nav-brand i { font-size: 1.4rem; }
        .nav-search { flex: 1; max-width: 320px; }
        .nav-search input { border-radius: 25px; border: 1.5px solid #e0f5f0; background: #f8fffe; font-size: .85rem; padding: 6px 16px; }
        .nav-search input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(0,184,148,.1); background: white; }
        .wallet-chip {
            background: linear-gradient(135deg, var(--primary), #00cec9);
            color: white; border-radius: 25px; padding: 5px 10px;
            font-size: .78rem; font-weight: 700; text-decoration: none;
            display: flex; align-items: center; gap: 4px; white-space: nowrap;
            cursor: pointer; border: none; max-width: 120px;
        }
        .wallet-chip:hover { opacity: .9; color: white; }
        .nav-avatar { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary); cursor: pointer; }
        /* ── Bottom Nav ── */
        .bottom-nav {
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 1030;
            height: var(--bottom-h);
            background: white;
            border-top: 1px solid #e8f5f0;
            box-shadow: 0 -4px 20px rgba(0,0,0,.06);
            display: flex; align-items: center;
        }
        .bottom-nav a {
            flex: 1; display: flex; flex-direction: column; align-items: center;
            justify-content: center; gap: 3px; text-decoration: none;
            color: #b2bec3; font-size: .62rem; font-weight: 600;
            transition: color .2s; padding: 6px 0; position: relative;
        }
        .bottom-nav a i { font-size: 1.35rem; }
        .bottom-nav a.active { color: var(--primary); }
        .bottom-nav a.active::before {
            content: ''; position: absolute; top: 0; left: 50%; transform: translateX(-50%);
            width: 32px; height: 3px; background: var(--primary); border-radius: 0 0 4px 4px;
        }
        .bottom-nav a .badge-dot {
            position: absolute; top: 4px; right: calc(50% - 14px);
            width: 8px; height: 8px; background: #e17055; border-radius: 50%; border: 2px solid white;
        }
        /* ── Cards ── */
        .card { border: none; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,.05); }
        /* ── Buttons ── */
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover, .btn-primary:focus { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-outline-primary { color: var(--primary); border-color: var(--primary); }
        .btn-outline-primary:hover { background: var(--primary); border-color: var(--primary); }
        /* ── Alerts ── */
        .alert-success { background: var(--primary-light); border-color: var(--primary); color: var(--primary-dark); }
        /* ── Form ── */
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(0,184,148,.1); }
        /* ── Badges ── */
        .badge-online { background: #00b894; color: white; font-size: .62rem; padding: 3px 8px; border-radius: 20px; }
        .badge-offline { background: #b2bec3; color: white; font-size: .62rem; padding: 3px 8px; border-radius: 20px; }
        /* ── Misc ── */
        .text-primary { color: var(--primary) !important; }
        .bg-primary { background: var(--primary) !important; }
        .section-title { font-weight: 700; color: var(--dark); font-size: 1rem; }
        /* ── Overflow fix ── */
        .min-w-0 { min-width: 0; }
        .text-truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .flex-shrink-0 { flex-shrink: 0 !important; }
        /* ── Amount no-wrap ── */
        .fw-bold, td.fw-bold { white-space: nowrap; }
        @media (max-width: 767px) {
            .fs-2 { font-size: clamp(1.2rem, 5vw, 2rem) !important; }
            .fs-3 { font-size: clamp(1.1rem, 4vw, 1.75rem) !important; }
        }
        /* ── Incoming call modal ── */
        .call-ring-modal .modal-content { border-radius: 24px; border: none; overflow: hidden; }
        .call-ring-avatar { width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 4px solid var(--primary); animation: ring-pulse 1.5s ease-in-out infinite; }
        @keyframes ring-pulse { 0%,100%{box-shadow:0 0 0 0 rgba(0,184,148,.4)} 50%{box-shadow:0 0 0 16px rgba(0,184,148,0)} }
        /* ── Desktop hide bottom nav ── */
        @media (min-width: 768px) {
            body { padding-bottom: 0; }
            .bottom-nav { display: none; }
        }
        /* ── Pagination ── */
        .pagination .page-link { color: var(--primary); border-radius: 8px; margin: 0 2px; border: 1.5px solid #e0f5f0; }
        .pagination .page-link:hover { background: var(--primary-light); color: var(--primary); }
        .pagination .page-item.active .page-link { background: var(--primary); border-color: var(--primary); color: white; }
        .pagination .page-item.disabled .page-link { color: #b2bec3; border-color: #eee; }
        /* ── Footer ── */
        .site-footer { background: white; border-top: 1px solid #e8f5f0; padding: 32px 0 16px; margin-top: 40px; }
        .site-footer .footer-brand { color: var(--primary); font-weight: 800; font-size: 1.2rem; }
        .site-footer a { color: #636e72; text-decoration: none; font-size: .85rem; }
        .site-footer a:hover { color: var(--primary); }
        .site-footer .footer-bottom { border-top: 1px solid #f0f0f0; margin-top: 20px; padding-top: 14px; }
        @media (max-width: 767px) { .site-footer { margin-bottom: var(--bottom-h); } }
    </style>
    @stack('styles')
</head>
<body>

{{-- ── Top Navbar ── --}}
<nav class="top-navbar">
    <a href="{{ route('home') }}" class="nav-brand">
        <i class="bi bi-camera-video-fill"></i>
        <span>LiveCall</span>
    </a>

    <div class="nav-search d-none d-md-block">
        <form action="{{ route('home') }}" method="GET">
            <input type="text" name="search" class="form-control" placeholder="🔍 Search models, countries..." value="{{ request('search') }}">
        </form>
    </div>

    <div class="ms-auto d-flex align-items-center gap-2">
        @auth
            {{-- Wallet Chip --}}
            <button class="wallet-chip" data-bs-toggle="modal" data-bs-target="#walletModal">
                <i class="bi bi-wallet2"></i>
                <span>₹{{ number_format(auth()->user()->wallet_balance, 2) }}</span>
                <i class="bi bi-plus-circle-fill" style="font-size:.8rem"></i>
            </button>

            {{-- Notifications --}}
            <div class="dropdown">
                <button class="btn btn-sm btn-light rounded-circle p-2 position-relative" data-bs-toggle="dropdown">
                    <i class="bi bi-bell fs-6"></i>
                    @php $unread = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp
                    @if($unread > 0)
                        <span class="position-absolute top-0 end-0 badge rounded-pill bg-danger" style="font-size:.55rem;padding:2px 4px">{{ $unread }}</span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="min-width:280px;border-radius:14px">
                    <li><h6 class="dropdown-header">Notifications</h6></li>
                    @php $notifs = \App\Models\Notification::where('user_id', auth()->id())->latest()->take(5)->get(); @endphp
                    @forelse($notifs as $n)
                        <li><a class="dropdown-item small py-2" href="#">
                            <div class="fw-semibold">{{ $n->title }}</div>
                            <div class="text-muted" style="font-size:.75rem">{{ $n->message }}</div>
                        </a></li>
                    @empty
                        <li><span class="dropdown-item text-muted small">No notifications</span></li>
                    @endforelse
                </ul>
            </div>

            {{-- User Avatar Dropdown --}}
            <div class="dropdown">
                <img src="{{ auth()->user()->avatar_url }}"
                     class="nav-avatar"
                     data-bs-toggle="dropdown"
                     alt="{{ auth()->user()->name }}"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&size=80&background=00b894&color=fff&bold=true'">
                <ul class="dropdown-menu dropdown-menu-end" style="border-radius:14px;min-width:220px">
                    <li class="px-3 py-2">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ auth()->user()->avatar_url }}" width="40" height="40" class="rounded-circle" style="object-fit:cover"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&size=80&background=00b894&color=fff&bold=true'">
                            <div>
                                <div class="fw-bold small">{{ auth()->user()->name }}</div>
                                <div class="text-muted" style="font-size:.72rem">{{ ucfirst(auth()->user()->role) }}</div>
                            </div>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    @if(auth()->user()->isModel())
                        <li><a class="dropdown-item" href="{{ route('model.dashboard') }}"><i class="bi bi-speedometer2 me-2 text-primary"></i>Model Dashboard</a></li>
                        <li><a class="dropdown-item" href="{{ route('model.earnings') }}"><i class="bi bi-cash-stack me-2 text-success"></i>Earnings</a></li>
                        <li><a class="dropdown-item" href="{{ route('model.profile.edit') }}"><i class="bi bi-person-gear me-2 text-info"></i>Edit Profile</a></li>
                    @endif
                    @if(auth()->user()->isAdmin())
                        <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-check me-2 text-warning"></i>Admin Panel</a></li>
                    @endif
                    <li><a class="dropdown-item" href="{{ route('call.history') }}"><i class="bi bi-clock-history me-2 text-secondary"></i>Call History</a></li>
                    <li><a class="dropdown-item" href="{{ route('wallet') }}"><i class="bi bi-wallet2 me-2 text-primary"></i>Wallet</a></li>
                    <li><a class="dropdown-item" href="{{ route('favorites') }}"><i class="bi bi-heart me-2 text-danger"></i>Favorites</a></li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary px-3" style="border-radius:25px">Login</a>
            <a href="{{ route('register') }}" class="btn btn-sm btn-primary px-3" style="border-radius:25px">Register</a>
        @endauth
    </div>
</nav>

{{-- ── Main Content ── --}}
<main class="container-fluid px-3 px-md-4 py-3" style="max-width:1200px;margin:0 auto">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            @foreach($errors->all() as $error) {{ $error }}<br> @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @yield('content')
</main>

{{-- ── Bottom Navigation (Mobile) ── --}}
<nav class="bottom-nav d-md-none">
    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="bi bi-house-fill"></i><span>Home</span>
    </a>
    <a href="{{ route('favorites') }}" class="{{ request()->routeIs('favorites') ? 'active' : '' }}">
        <i class="bi bi-heart-fill"></i><span>Favorites</span>
    </a>
    <a href="{{ route('chat') }}" class="{{ request()->routeIs('chat*') ? 'active' : '' }}">
        <i class="bi bi-chat-dots-fill"></i><span>Chat</span>
        @auth
            @php $unreadMsg = \App\Models\Message::where('receiver_id', auth()->id())->where('is_read', false)->count(); @endphp
            @if($unreadMsg > 0)<span class="badge-dot"></span>@endif
        @endauth
    </a>
    <a href="{{ route('wallet') }}" class="{{ request()->routeIs('wallet*') ? 'active' : '' }}">
        <i class="bi bi-wallet2"></i><span>Wallet</span>
    </a>
    <a href="{{ route('call.history') }}" class="{{ request()->routeIs('call.history') ? 'active' : '' }}">
        <i class="bi bi-clock-history"></i><span>History</span>
    </a>
</nav>

{{-- ── Wallet Recharge Modal ── --}}
<div class="modal fade" id="walletModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="fw-bold mb-0"><i class="bi bi-wallet2 text-primary me-2"></i>Recharge Wallet</h5>
                    @auth<small class="text-muted">Current: ₹{{ number_format(auth()->user()->wallet_balance, 2) }}</small>@endauth
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-2">Quick amounts:</p>
                <div class="d-flex gap-2 flex-wrap mb-3">
                    @foreach([100, 200, 500, 1000, 2000, 5000] as $amt)
                        <button class="btn btn-outline-primary btn-sm quick-amount px-3" data-amount="{{ $amt }}" style="border-radius:20px">₹{{ $amt }}</button>
                    @endforeach
                </div>
                <div class="input-group">
                    <span class="input-group-text bg-white">₹</span>
                    <input type="number" id="rechargeAmount" class="form-control" placeholder="Enter custom amount" min="10" max="50000">
                </div>
                <div id="rechargeError" class="text-danger small mt-1 d-none"></div>
                <p class="text-muted small mt-2 mb-0"><i class="bi bi-shield-check text-success me-1"></i>100% secure payment via Razorpay</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button class="btn btn-primary w-100 fw-bold py-2" id="proceedRecharge" style="border-radius:12px">
                    <i class="bi bi-credit-card me-2"></i>Proceed to Pay
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ── Incoming Call Modal ── --}}
<div class="modal fade call-ring-modal" id="incomingCallModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-center p-4">
            <div class="mb-1">
                <span class="badge bg-success px-3 py-1 mb-3" style="border-radius:20px">
                    <i class="bi bi-telephone-fill me-1"></i>Incoming Call
                </span>
            </div>
            <img id="callerAvatar" src="" class="call-ring-avatar mx-auto mb-3" alt="Caller">
            <h5 id="callerName" class="fw-bold mb-1"></h5>
            <p class="text-muted mb-4"><span id="callTypeLabel"></span> Call</p>
            <div class="d-flex gap-3 justify-content-center">
                <div class="text-center">
                    <button class="btn btn-danger rounded-circle p-0 d-flex align-items-center justify-content-center mb-1" id="rejectCallBtn" style="width:58px;height:58px;font-size:1.3rem">
                        <i class="bi bi-telephone-x-fill"></i>
                    </button>
                    <small class="text-muted">Decline</small>
                </div>
                <div class="text-center">
                    <button class="btn btn-success rounded-circle p-0 d-flex align-items-center justify-content-center mb-1" id="acceptCallBtn" style="width:58px;height:58px;font-size:1.3rem">
                        <i class="bi bi-telephone-fill"></i>
                    </button>
                    <small class="text-muted">Accept</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Site Footer ── --}}
<footer class="site-footer">
    <div class="container-fluid px-3 px-md-4" style="max-width:1200px;margin:0 auto">
        <div class="row g-4">
            <div class="col-12 col-md-4">
                <div class="footer-brand d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-camera-video-fill"></i> LiveCall
                </div>
                <p class="text-muted small mb-2">Connect live with top models worldwide via audio & video calls.</p>
                <div class="d-flex gap-3">
                    <a href="#"><i class="bi bi-instagram fs-5"></i></a>
                    <a href="#"><i class="bi bi-twitter-x fs-5"></i></a>
                    <a href="#"><i class="bi bi-facebook fs-5"></i></a>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="fw-semibold small mb-2 text-dark">Platform</div>
                <ul class="list-unstyled mb-0">
                    <li class="mb-1"><a href="{{ route('home') }}">Browse Models</a></li>
                    @auth
                    <li class="mb-1"><a href="{{ route('favorites') }}">Favorites</a></li>
                    <li class="mb-1"><a href="{{ route('call.history') }}">Call History</a></li>
                    <li class="mb-1"><a href="{{ route('wallet') }}">Wallet</a></li>
                    @else
                    <li class="mb-1"><a href="{{ route('login') }}">Login</a></li>
                    <li class="mb-1"><a href="{{ route('register') }}">Register</a></li>
                    @endauth
                </ul>
            </div>
            <div class="col-6 col-md-2">
                <div class="fw-semibold small mb-2 text-dark">Support</div>
                <ul class="list-unstyled mb-0">
                    <li class="mb-1"><a href="{{ route('help-center') }}">Help Center</a></li>
                    <li class="mb-1"><a href="{{ route('safety') }}">Safety</a></li>
                    <li class="mb-1"><a href="{{ route('contact') }}">Contact Us</a></li>
                </ul>
            </div>
            <div class="col-12 col-md-4">
                <div class="fw-semibold small mb-2 text-dark">Secure Payments</div>
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <span class="badge bg-light text-dark border px-2 py-1"><i class="bi bi-shield-check text-success me-1"></i>Razorpay</span>
                    <span class="badge bg-light text-dark border px-2 py-1"><i class="bi bi-lock-fill text-primary me-1"></i>SSL Secured</span>
                    <span class="badge bg-light text-dark border px-2 py-1"><i class="bi bi-credit-card me-1"></i>UPI / Cards</span>
                </div>
                <p class="text-muted small mt-2 mb-0"><i class="bi bi-geo-alt me-1"></i>Available worldwide</p>
            </div>
        </div>
        <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <small class="text-muted">© {{ date('Y') }} LiveCall. All rights reserved.</small>
            <div class="d-flex gap-3">
                <a href="{{ route('privacy-policy') }}" class="small">Privacy Policy</a>
                <a href="{{ route('terms') }}" class="small">Terms of Service</a>
                <a href="{{ route('refund-policy') }}" class="small">Refund Policy</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

@auth
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
// ── Pusher Init ──────────────────────────────────────────────
const pusher = new Pusher('{{ env("PUSHER_APP_KEY", "demo") }}', {
    cluster: '{{ env("PUSHER_APP_CLUSTER", "mt1") }}',
    authEndpoint: '/broadcasting/auth',
    auth: { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } }
});
const userChannel = pusher.subscribe('private-user.{{ auth()->id() }}');

// ── Incoming Call ────────────────────────────────────────────
let currentCallId = null;
userChannel.bind('incoming.call', function(data) {
    currentCallId = data.call_id;
    document.getElementById('callerAvatar').src = data.caller_avatar;
    document.getElementById('callerName').textContent = data.caller_name;
    document.getElementById('callTypeLabel').textContent = data.call_type === 'video' ? '📹 Video' : '🎙 Audio';
    new bootstrap.Modal(document.getElementById('incomingCallModal')).show();
    // Ring sound
    try { new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAA==').play(); } catch(e) {}
});

document.getElementById('acceptCallBtn').addEventListener('click', function() {
    fetch('/call/' + currentCallId + '/accept', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json' }
    }).then(r => r.json()).then(() => {
        bootstrap.Modal.getInstance(document.getElementById('incomingCallModal')).hide();
        window.location.href = '/call/' + currentCallId + '/room';
    });
});

document.getElementById('rejectCallBtn').addEventListener('click', function() {
    fetch('/call/' + currentCallId + '/reject', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    });
    bootstrap.Modal.getInstance(document.getElementById('incomingCallModal')).hide();
});

// ── Wallet Recharge ──────────────────────────────────────────
document.querySelectorAll('.quick-amount').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.quick-amount').forEach(b => b.classList.remove('btn-primary'));
        btn.classList.add('btn-primary');
        btn.classList.remove('btn-outline-primary');
        document.getElementById('rechargeAmount').value = btn.dataset.amount;
    });
});

document.getElementById('proceedRecharge').addEventListener('click', function() {
    const amount = parseFloat(document.getElementById('rechargeAmount').value);
    const errEl = document.getElementById('rechargeError');
    if (!amount || amount < 10) {
        errEl.textContent = 'Minimum recharge is ₹10'; errEl.classList.remove('d-none'); return;
    }
    errEl.classList.add('d-none');
    this.disabled = true;
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

    fetch('/wallet/order', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json' },
        body: JSON.stringify({ amount })
    }).then(r => r.json()).then(order => {
        this.disabled = false;
        this.innerHTML = '<i class="bi bi-credit-card me-2"></i>Proceed to Pay';
        const rzp = new Razorpay({
            key: order.key || '{{ env("RAZORPAY_KEY","rzp_test_demo") }}',
            amount: order.amount * 100,
            currency: order.currency || 'INR',
            order_id: order.order_id,
            name: 'LiveCall',
            description: 'Wallet Recharge',
            image: 'https://ui-avatars.com/api/?name=LC&background=00b894&color=fff&bold=true',
            theme: { color: '#00b894' },
            handler: function(response) {
                fetch('/wallet/verify', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ...response, amount })
                }).then(r => r.json()).then(res => {
                    if (res.success) {
                        bootstrap.Modal.getInstance(document.getElementById('walletModal')).hide();
                        // Update wallet display
                        document.querySelectorAll('.wallet-chip span').forEach(el => {
                            el.textContent = '₹' + parseFloat(res.balance).toFixed(2);
                        });
                        showToast('✅ Wallet recharged with ₹' + amount + '!', 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast('❌ Payment verification failed', 'danger');
                    }
                });
            }
        });
        rzp.open();
    }).catch(() => {
        this.disabled = false;
        this.innerHTML = '<i class="bi bi-credit-card me-2"></i>Proceed to Pay';
        showToast('Payment service unavailable. Check Razorpay keys.', 'warning');
    });
});

// ── Real-time unread chat badge ────────────────────────────
const chatChannel2 = pusher.subscribe('private-chat.{{ auth()->id() }}');
chatChannel2.bind('message.sent', function() {
    document.querySelectorAll('.badge-dot').forEach(el => el.style.display = 'block');
});

function showToast(msg, type = 'success') {
    const t = document.createElement('div');
    t.className = `alert alert-${type} position-fixed bottom-0 end-0 m-3 shadow`;
    t.style.cssText = 'z-index:9999;border-radius:12px;min-width:250px';
    t.innerHTML = msg;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 3000);
}
</script>
@endauth

@stack('scripts')
</body>
</html>
