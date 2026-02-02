<!doctype html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - DawaPlus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body{min-height:100vh;background: radial-gradient(1200px 600px at 0% 0%, rgba(13,110,253,.18), transparent 50%), radial-gradient(900px 600px at 100% 0%, rgba(32,201,151,.16), transparent 55%), linear-gradient(180deg, rgba(2,6,23,.04), rgba(2,6,23,.00));}
        .cardx{border-radius:24px; box-shadow:0 24px 70px rgba(0,0,0,.12); border:1px solid rgba(0,0,0,.06)}
        .form-control{border-radius:14px; padding:.75rem .9rem;}
        .btn{border-radius:14px; padding:.7rem .95rem; font-weight:800;}
        .brand{letter-spacing:.2px}
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-3">
<div class="container" style="max-width: 520px">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-4 d-flex align-items-center justify-content-center" style="width:46px;height:46px;background:rgba(13,110,253,.16)"><i class="bi bi-capsule-pill fs-3 text-primary"></i></div>
            <div>
                <div class="brand fw-black fs-4">DawaPlus</div>
                <div class="text-muted">Admin Console</div>
            </div>
        </div>
        <button class="btn btn-outline-secondary" id="themeBtn" type="button"><i class="bi bi-moon-stars"></i></button>
    </div>

    @if(session('err'))
        <div class="alert alert-danger border-0 cardx">{{ session('err') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger border-0 cardx">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card cardx">
        <div class="card-body p-4">
            <div class="fw-black fs-4 mb-1">Sign in</div>
            <div class="text-muted mb-4">Use your admin account to manage the app content.</div>
            <form method="POST" action="{{ route('admin.login') }}" class="d-grid gap-3">
                @csrf
                <div>
                    <label class="form-label fw-semibold">Email</label>
                    <input class="form-control" name="email" type="email" value="{{ old('email') }}" required autofocus>
                </div>
                <div>
                    <label class="form-label fw-semibold">Password</label>
                    <input class="form-control" name="password" type="password" required>
                </div>
                <button class="btn btn-primary" type="submit"><i class="bi bi-arrow-right-circle me-1"></i>Continue</button>
            </form>
        </div>
    </div>
</div>

<script>
    const root = document.documentElement;
    const stored = localStorage.getItem('dawaplus_admin_theme');
    if (stored) root.setAttribute('data-bs-theme', stored);
    document.getElementById('themeBtn')?.addEventListener('click', () => {
        const cur = root.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light';
        const next = cur === 'dark' ? 'light' : 'dark';
        root.setAttribute('data-bs-theme', next);
        localStorage.setItem('dawaplus_admin_theme', next);
    });
</script>
</body>
</html>
