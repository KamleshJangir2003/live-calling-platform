<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - LiveCall Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --primary: #00b894; --primary-dark: #00a381; --sidebar-width: 240px; }
        body { background: #f4f6f9; }
        .sidebar { width: var(--sidebar-width); min-height: 100vh; background: #1a1a2e; position: fixed; top: 0; left: 0; z-index: 1050; transition: transform 0.3s; }
        .sidebar-brand { padding: 20px; color: var(--primary); font-weight: 700; font-size: 1.3rem; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: space-between; }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); padding: 10px 20px; border-radius: 8px; margin: 2px 8px; transition: all 0.2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: var(--primary); color: white; }
        .sidebar .nav-link i { width: 20px; }
        .main-content { margin-left: var(--sidebar-width); padding: 20px; }
        .top-bar { background: white; border-radius: 12px; padding: 12px 20px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; }
        .stat-card { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow: hidden; }
        .stat-card .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0; }
        .stat-card .stat-value { font-size: 1.1rem; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .stat-card .stat-label { font-size: .72rem; color: #636e72; line-height: 1.3; word-break: break-word; }
        @media (max-width: 576px) {
            .stat-card { padding: 10px; }
            .stat-card .stat-inner { flex-direction: column; align-items: flex-start !important; gap: 6px !important; }
            .stat-card .stat-icon { width: 32px; height: 32px; font-size: .9rem; border-radius: 8px; }
            .stat-card .stat-value { font-size: .95rem; }
            .stat-card .stat-label { font-size: .68rem; }
        }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .badge-pending { background: #fdcb6e; color: #2d3436; }
        .badge-approved { background: #00b894; color: white; }
        .badge-rejected { background: #d63031; color: white; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1040; }
        .sidebar-overlay.show { display: block; }
        .btn-hamburger { background: none; border: none; color: white; font-size: 1.2rem; cursor: pointer; padding: 0; display: none; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 12px; }
            .top-bar { border-radius: 8px; padding: 10px 14px; }
            .btn-hamburger { display: inline-block; }
            .stat-card { padding: 14px; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <span><i class="bi bi-camera-video-fill me-2"></i>LiveCall Admin</span>
            <button class="btn-hamburger" onclick="closeSidebar()"><i class="bi bi-x-lg"></i></button>
        </div>
        <nav class="nav flex-column mt-3">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                <i class="bi bi-people me-2"></i>Users
            </a>
            <a class="nav-link {{ request()->routeIs('admin.models') ? 'active' : '' }}" href="{{ route('admin.models') }}">
                <i class="bi bi-person-badge me-2"></i>Models
            </a>
            <a class="nav-link {{ request()->routeIs('admin.withdrawals') ? 'active' : '' }}" href="{{ route('admin.withdrawals') }}">
                <i class="bi bi-cash-stack me-2"></i>Withdrawals
            </a>
            <a class="nav-link {{ request()->routeIs('admin.transactions') ? 'active' : '' }}" href="{{ route('admin.transactions') }}">
                <i class="bi bi-receipt me-2"></i>Transactions
            </a>
            <a class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}" href="{{ route('admin.reports') }}">
                <i class="bi bi-bar-chart me-2"></i>Reports
            </a>
            <a class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}" href="{{ route('admin.settings') }}">
                <i class="bi bi-gear me-2"></i>Settings
            </a>
            <hr style="border-color:rgba(255,255,255,0.1)">
            <a class="nav-link" href="{{ route('home') }}"><i class="bi bi-globe me-2"></i>View Site</a>
            <form action="{{ route('logout') }}" method="POST" class="px-2">
                @csrf
                <button class="nav-link border-0 bg-transparent text-danger w-100 text-start">
                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                </button>
            </form>
        </nav>
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <div class="main-content">
        <div class="top-bar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn-hamburger d-md-none" style="color:#1a1a2e;font-size:1.3rem" onclick="openSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <h6 class="mb-0 fw-bold">@yield('title', 'Dashboard')</h6>
            </div>
            <span class="text-muted small">{{ auth()->user()->name }}</span>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                @foreach($errors->all() as $error) {{ $error }}<br> @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openSidebar() {
            document.getElementById('adminSidebar').classList.add('open');
            document.getElementById('sidebarOverlay').classList.add('show');
        }
        function closeSidebar() {
            document.getElementById('adminSidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('show');
        }
    </script>
    @stack('scripts')
</body>
</html>
