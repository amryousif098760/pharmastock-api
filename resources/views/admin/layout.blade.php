<!doctype html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Admin') - DawaPlus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root{--sidebar: 288px;}
        .app-shell{min-height:100vh; display:flex;}
        .sidebar{width:var(--sidebar); flex:0 0 var(--sidebar); border-right:1px solid rgba(255,255,255,.08); background: radial-gradient(1200px 600px at 0% 0%, rgba(13,110,253,.22), transparent 50%), radial-gradient(800px 500px at 100% 0%, rgba(32,201,151,.18), transparent 55%), linear-gradient(180deg, rgba(13,110,253,.12), rgba(0,0,0,0));}
        [data-bs-theme="light"] .sidebar{border-right:1px solid rgba(0,0,0,.06);}
        .brand{letter-spacing:.2px;}
        .nav-pill{border-radius:14px; padding:.6rem .8rem;}
        .nav-pill.active{background: rgba(13,110,253,.14);}
        [data-bs-theme="dark"] .nav-pill.active{background: rgba(13,110,253,.22);}
        .content{flex:1;}
        .topbar{position:sticky; top:0; z-index:5; backdrop-filter: blur(10px); background: rgba(255,255,255,.7); border-bottom:1px solid rgba(0,0,0,.06)}
        [data-bs-theme="dark"] .topbar{background: rgba(15,23,42,.6); border-bottom:1px solid rgba(255,255,255,.08)}
        .card-glow{box-shadow: 0 14px 40px rgba(0,0,0,.08); border:1px solid rgba(0,0,0,.06)}
        [data-bs-theme="dark"] .card-glow{box-shadow: 0 18px 55px rgba(0,0,0,.35); border:1px solid rgba(255,255,255,.08)}
        .form-control, .form-select{border-radius: 14px; padding:.7rem .9rem;}
        .btn{border-radius: 14px; padding:.65rem .95rem; font-weight:700;}
        .table thead th{font-size:.82rem; color:rgba(0,0,0,.55);}
        [data-bs-theme="dark"] .table thead th{color:rgba(255,255,255,.65)}
        .badge-soft{padding:.5rem .6rem; border-radius: 999px; font-weight:800;}
        .badge-soft.primary{background: rgba(13,110,253,.14); color: #0d6efd;}
        .badge-soft.success{background: rgba(25,135,84,.14); color: #198754;}
        .badge-soft.warning{background: rgba(255,193,7,.18); color: #b38a00;}
        .badge-soft.danger{background: rgba(220,53,69,.14); color: #dc3545;}
        [data-bs-theme="dark"] .badge-soft.primary{background: rgba(13,110,253,.22);}
        [data-bs-theme="dark"] .badge-soft.success{background: rgba(25,135,84,.22);}
        [data-bs-theme="dark"] .badge-soft.warning{background: rgba(255,193,7,.20); color:#ffd15c;}
        [data-bs-theme="dark"] .badge-soft.danger{background: rgba(220,53,69,.22);}
        .sidebar .small-muted{color: rgba(255,255,255,.7)}
        [data-bs-theme="light"] .sidebar .small-muted{color: rgba(0,0,0,.55)}
    </style>
</head>
<body>
<div class="app-shell">
    <aside class="sidebar p-3">
        <div class="d-flex align-items-center gap-2 mb-3">
            <div class="rounded-4 d-flex align-items-center justify-content-center" style="width:42px;height:42px;background:rgba(13,110,253,.18)"><i class="bi bi-capsule-pill fs-4 text-primary"></i></div>
            <div>
                <div class="brand fw-black fs-5">DawaPlus</div>
                <div class="small small-muted">Admin Console</div>
            </div>
        </div>

        <div class="my-3">
            <div class="input-group">
                <span class="input-group-text rounded-start-4"><i class="bi bi-search"></i></span>
                <input class="form-control rounded-end-4" placeholder="Quick filter" id="quickFilter" />
            </div>
        </div>

        @php
            $active = request()->route()?->getName();
            $item = fn($name) => str_starts_with((string)$active, $name);
        @endphp

        <nav class="d-grid gap-2" id="navList">
            <a class="nav-pill d-flex align-items-center gap-2 text-decoration-none {{ $item('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>
            <a class="nav-pill d-flex align-items-center gap-2 text-decoration-none {{ $item('admin.pharmacies') ? 'active' : '' }}" href="{{ route('admin.pharmacies.index') }}"><i class="bi bi-hospital"></i><span>Pharmacies</span></a>
            <a class="nav-pill d-flex align-items-center gap-2 text-decoration-none {{ $item('admin.orders') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}"><i class="bi bi-receipt"></i><span>Orders</span></a>
            <a class="nav-pill d-flex align-items-center gap-2 text-decoration-none {{ $item('admin.warehouses') ? 'active' : '' }}" href="{{ route('admin.warehouses.index') }}"><i class="bi bi-building"></i><span>Warehouses</span></a>
            <a class="nav-pill d-flex align-items-center gap-2 text-decoration-none {{ $item('admin.medicines') ? 'active' : '' }}" href="{{ route('admin.medicines.index') }}"><i class="bi bi-capsule"></i><span>Medicines</span></a>
            <a class="nav-pill d-flex align-items-center gap-2 text-decoration-none {{ $item('admin.categories') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}"><i class="bi bi-grid"></i><span>Categories</span></a>
            <a class="nav-pill d-flex align-items-center gap-2 text-decoration-none {{ $item('admin.banners') ? 'active' : '' }}" href="{{ route('admin.banners.index') }}"><i class="bi bi-images"></i><span>Banners</span></a>
        </nav>

        <div class="mt-4 p-3 rounded-4" style="background: rgba(0,0,0,.06)">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="fw-bold">Theme</div>
                    <div class="small text-muted">Light / Dark</div>
                </div>
                <button class="btn btn-outline-secondary" id="themeBtn" type="button"><i class="bi bi-moon-stars"></i></button>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.logout') }}" class="mt-3">
            @csrf
            <button class="btn btn-outline-danger w-100" type="submit"><i class="bi bi-box-arrow-right me-1"></i>Logout</button>
        </form>
    </aside>

    <main class="content">
        <div class="topbar px-3 py-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="fw-black fs-4 mb-0">@yield('header')</div>
                    <div class="text-muted">@yield('subheader')</div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge-soft primary"><i class="bi bi-person-circle me-1"></i>{{ auth()->user()->email ?? '' }}</span>
                </div>
            </div>
        </div>

        <div class="p-3 p-lg-4">
            @if(session('ok'))
                <div class="alert alert-success card-glow border-0">{{ session('ok') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger card-glow border-0">
                    <div class="fw-bold mb-2">Fix the following</div>
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const root = document.documentElement;
    const stored = localStorage.getItem('dawaplus_admin_theme');
    if (stored) root.setAttribute('data-bs-theme', stored);
    const btn = document.getElementById('themeBtn');
    btn?.addEventListener('click', () => {
        const cur = root.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light';
        const next = cur === 'dark' ? 'light' : 'dark';
        root.setAttribute('data-bs-theme', next);
        localStorage.setItem('dawaplus_admin_theme', next);
    });
    const qf = document.getElementById('quickFilter');
    qf?.addEventListener('input', (e) => {
        const v = (e.target.value || '').toLowerCase();
        document.querySelectorAll('#navList a').forEach(a => {
            a.style.display = a.textContent.toLowerCase().includes(v) ? '' : 'none';
        });
    });
</script>
</body>
</html>
