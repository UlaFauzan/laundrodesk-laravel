<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LaundroDesk')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    @stack('styles')
</head>
<body>
    @php
        $currentUser = auth()->user();
        $currentRole = strtolower($currentUser?->role?->nama_role ?? '');

        $menus = [
            'admin' => [
                ['label' => 'Dashboard Admin', 'href' => '/admin', 'match' => 'admin'],
                ['label' => 'Kelola Role', 'href' => '/roles', 'match' => 'roles*'],
                ['label' => 'Kelola User', 'href' => '/users', 'match' => 'users*'],
                ['label' => 'Kelola Layanan', 'href' => '/layanan', 'match' => 'layanan*'],
                ['label' => 'Kelola Status Laundry', 'href' => '/status-laundry', 'match' => 'status-laundry*'],
                ['label' => 'Kelola Pelanggan', 'href' => '/pelanggan', 'match' => 'pelanggan'],
                ['label' => 'Log Aktivitas', 'href' => '/log-aktivitas', 'match' => 'log-aktivitas*'],
                ['label' => 'Laporan Error', 'href' => '/error-reports', 'match' => 'error-reports*'],
            ],
            'kasir' => [
                ['label' => 'Dashboard Kasir', 'href' => '/kasir/dashboard', 'match' => 'kasir/dashboard'],
                ['label' => 'Transaksi', 'href' => '/transaksi', 'match' => 'transaksi*'],
                ['label' => 'Pembayaran', 'href' => '/pembayaran', 'match' => 'pembayaran*'],
                ['label' => 'Detail Transaksi', 'href' => '/detail-transaksi', 'match' => 'detail-transaksi*'],
                ['label' => 'Notifikasi', 'href' => '/notifikasi', 'match' => 'notifikasi*'],
            ],
            'manager' => [
                ['label' => 'Laporan Pendapatan', 'href' => '/laporan-pendapatan', 'match' => 'laporan-pendapatan*'],
            ],
        ];

        $menuItems = $menus[$currentRole] ?? [];
        $userLabel = $currentUser?->username ?? $currentUser?->name ?? $currentUser?->email ?? 'Pengguna';
        $roleLabel = $currentUser?->role?->nama_role ?? 'N/A';
    @endphp

    <div class="app-shell {{ empty($menuItems) ? 'app-shell--single' : '' }}">
        <header class="topbar">
            <div class="topbar__brand">
                @if($currentRole === 'pelanggan')
                    <button class="icon-button" type="button" onclick="history.back()" aria-label="Kembali">
                        <span aria-hidden="true">&larr;</span>
                    </button>
                @endif
                <a class="brand-mark" href="{{ $currentRole === 'pelanggan' ? route('pelanggan.profile') : url('/' . ($currentRole === 'admin' ? 'admin' : ($currentRole === 'manager' ? 'laporan-pendapatan' : 'kasir/dashboard'))) }}">
                    <span class="brand-mark__icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </span>
                    <span>
                        <strong>LaundroDesk</strong>
                        <small>{{ $roleLabel }}</small>
                    </span>
                </a>
            </div>

            <div class="topbar__account">
                <div class="account-chip" title="{{ $userLabel }}">
                    <span class="account-chip__avatar" aria-hidden="true">{{ strtoupper(substr($userLabel, 0, 1)) }}</span>
                    <span class="account-chip__text">{{ $userLabel }}</span>
                </div>
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-danger btn-compact" type="submit">Logout</button>
                    </form>
                @endauth
            </div>
        </header>

        @if(!empty($menuItems))
            <aside class="sidebar" aria-label="Navigasi utama">
                <nav>
                    <ul class="sidebar-menu">
                        @foreach($menuItems as $item)
                            <li>
                                <a class="{{ request()->is($item['match']) ? 'active' : '' }}" href="{{ url($item['href']) }}">
                                    {{ $item['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </aside>
        @endif

        <main class="content">
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
        </main>
    </div>

    @stack('scripts')
</body>
</html>
