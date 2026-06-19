<!DOCTYPE html>
<html>
<head>
    <title>Laundry Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root{
            --bg: #f6f5ff;
            --card: rgba(255,255,255,.78);
            --card-solid: #ffffff;
            --text: #1f1b2e;
            --muted: #6b6790;
            --primary: #7c3aed;       /* ungu muda */
            --primary-2: #a78bfa;     /* ungu pastel */
            --primary-ink: #ffffff;
            --danger: #ef4444;
            --success: #22c55e;
            --border: rgba(124,58,237,.18);
        }

        body { font-family: 'Merriweather', Georgia, 'Times New Roman', Times, serif; line-height: 1.6; background: var(--bg); color: var(--text); }

        .container { max-width: 1200px; margin: 0 auto; min-height: 100vh; }

        .navbar {
            position: sticky;
            top: 0;
            z-index: 10;
            background: rgba(255,255,255,.65);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 { font-size: 18px; font-weight: 800; color: var(--primary); }

        .back-btn {
            background: transparent;
            border: 1px solid transparent;
            color: var(--primary);
            font-weight: 800;
            padding: 6px 10px;
            margin-right: 10px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
        }

        .back-btn:hover { background: rgba(124,58,237,.06); border-color: rgba(124,58,237,.08); }

        .nav-links { display: flex; gap: 15px; align-items: center; }
        .nav-links span { color: var(--muted); font-size: 13px; }
        .nav-links a { color: var(--primary); text-decoration: none; padding: 5px 10px; }
        .nav-links a:hover { background: rgba(124,58,237,.08); border-radius: 10px; }
        .nav-links form { display: inline; }

        .nav-links button {
            background: rgba(239,68,68,.12);
            color: #b91c1c;
            border: 1px solid rgba(239,68,68,.25);
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 10px;
            font-weight: 700;
        }

        .nav-links button:hover { background: rgba(239,68,68,.18); }

        .sidebar {
            float: left;
            width: 240px;
            padding: 18px;
        }

        .sidebar-menu { list-style: none; }
        .sidebar-menu li { margin-bottom: 10px; }

        .sidebar-menu a {
            display: block;
            padding: 10px 12px;
            background: rgba(255,255,255,.6);
            border: 1px solid rgba(124,58,237,.12);
            text-decoration: none;
            color: var(--text);
            border-radius: 14px;
            transition: transform .08s ease, background .12s ease, border-color .12s ease;
            font-weight: 700;
            font-size: 13.5px;
        }

        .sidebar-menu a:hover {
            background: rgba(124,58,237,.07);
            border-color: rgba(124,58,237,.22);
            transform: translateY(-1px);
        }

        .sidebar-menu a.active {
            background: rgba(124,58,237,.12);
            border-color: rgba(124,58,237,.28);
            color: var(--primary);
        }

        .content {
            margin-left: 260px;
            padding: 22px;
        }

        .page-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 18px;
            box-shadow: 0 10px 30px rgba(124,58,237,.06);
        }

        .table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        .table th, .table td { border: 1px solid rgba(124,58,237,.12); padding: 12px; text-align: left; }
        .table th {
            background: linear-gradient(90deg, rgba(124,58,237,.95), rgba(167,139,250,.85));
            color: var(--primary-ink);
            border-color: rgba(124,58,237,.35);
            font-weight: 800;
        }
        .table tr:hover { background: rgba(167,139,250,.08); }

        .btn {
            padding: 9px 14px;
            margin-right: 6px;
            border: 1px solid transparent;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-weight: 800;
            font-size: 13px;
        }

        .btn-primary { background: rgba(124,58,237,.12); border-color: rgba(124,58,237,.25); color: var(--primary); }
        .btn-primary:hover { background: rgba(124,58,237,.18); }

        .btn-danger { background: rgba(239,68,68,.12); border-color: rgba(239,68,68,.25); color: #b91c1c; }
        .btn-danger:hover { background: rgba(239,68,68,.18); }

        .btn-success { background: rgba(34,197,94,.12); border-color: rgba(34,197,94,.25); color: #15803d; }
        .btn-success:hover { background: rgba(34,197,94,.18); }

        .alert { padding: 15px; border-radius: 14px; margin-bottom: 18px; border: 1px solid transparent; font-weight: 800; }
        .alert-success { background: rgba(34,197,94,.12); color: #166534; border-color: rgba(34,197,94,.25); }
        .alert-error { background: rgba(239,68,68,.12); color: #991b1b; border-color: rgba(239,68,68,.25); }
    </style>
</head>
<body>
    @php
        $currentUser = auth()->user();
        $currentRole = strtolower($currentUser->role?->nama_role ?? '');
    @endphp

    <div class="navbar">
        @if($currentRole === 'pelanggan')
            <button class="back-btn" onclick="history.back()" aria-label="Kembali">←</button>
        @endif
        <h1>🧺 Laundry Management</h1>
        <div class="nav-links">
            <span>{{ $currentUser->username ?? $currentUser->name ?? $currentUser->email }} ({{ $currentUser->role->nama_role ?? 'N/A' }})</span>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>

    <div class="sidebar">
        <ul class="sidebar-menu">
            @if($currentRole === 'admin')
                <li><a href="/admin">Dashboard Admin</a></li>
                <li><a href="/roles">Kelola Role</a></li>
                <li><a href="/users">Kelola User</a></li>
                <li><a href="/layanan">Kelola Layanan</a></li>
                <li><a href="/status-laundry">Kelola Status Laundry</a></li>
                <li><a href="/pelanggan">Kelola Pelanggan</a></li>
                <li><a href="/log-aktivitas">Log Aktivitas</a></li>
                <li><a href="/error-reports">📋 Laporan Error</a></li>

            @endif

            @if($currentRole === 'kasir')
                <li><a href="/transaksi">Transaksi</a></li>
                <li><a href="/pembayaran">Pembayaran</a></li>
                <li><a href="/detail-transaksi">Detail Transaksi</a></li>
                <li><a href="/notifikasi">Notifikasi</a></li>
            @endif

            @if($currentRole === 'manager')
                {{-- Manager dashboard has single page; sidebar link removed to avoid duplication --}}
            @endif

            @if($currentRole === 'pelanggan')
                {{-- khusus pelanggan: navigasi tombol ada di bawah profil, tidak ditampilkan di sidebar --}}
            @endif
        </ul>
    </div>

    <div class="content">
        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
