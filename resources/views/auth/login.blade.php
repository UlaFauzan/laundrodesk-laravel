<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - LaundroDesk</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <style>
        :root {
            --bg: #f0f9ff;
            --surface: #ffffff;
            --surface-soft: #f8faff;
            --text: #0f172a;
            --muted: #64748b;
            --primary: #0f6bff;
            --primary-strong: #0b4fba;
            --primary-soft: #eff6ff;
            --aqua: #06b6d4;
            --aqua-soft: #ecfeff;
            --aqua-active: #22d3ee;
            --border: #e2e8f0;
            --shadow: 0 1px 3px rgba(15, 107, 255, 0.06), 0 4px 24px rgba(15, 107, 255, 0.08);
            --shadow-sm: 0 1px 2px rgba(15, 107, 255, 0.04);
            --radius: 12px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { min-height: 100vh; }
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: var(--bg);
            color: var(--text);
            font-family: Inter, ui-sans-serif, system-ui, sans-serif;
            font-size: 14px;
            line-height: 1.55;
            -webkit-font-smoothing: antialiased;
        }

        .login-card {
            width: min(420px, 100%);
            padding: 32px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .login-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .login-brand__icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: var(--radius);
            background: linear-gradient(135deg, var(--primary), var(--primary-strong));
            color: #fff;
            font-size: 14px;
            font-weight: 800;
            box-shadow: 0 2px 8px rgba(15, 107, 255, 0.2);
        }

        .login-brand__title {
            font-size: 18px;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.02em;
        }

        .login-info {
            padding: 14px 16px;
            margin-bottom: 20px;
            background: var(--aqua-soft);
            border: 1px solid #a5f3fc;
            border-radius: var(--radius);
            color: #155e75;
            font-size: 13px;
            line-height: 1.5;
        }

        .form-group { margin-bottom: 16px; }

        label {
            display: block;
            margin-bottom: 6px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            min-height: 44px;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: #fff;
            color: var(--text);
            font-size: 14px;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.15);
        }

        input::placeholder { color: #94a3b8; }

        .help-text {
            margin-top: 6px;
            color: var(--muted);
            font-size: 12px;
        }

        button[type="submit"] {
            width: 100%;
            min-height: 44px;
            padding: 10px 16px;
            margin-top: 4px;
            border: 1px solid var(--primary);
            border-radius: var(--radius);
            background: var(--primary);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s ease;
            box-shadow: var(--shadow-sm);
        }

        button[type="submit"]:hover {
            background: var(--primary-strong);
            border-color: var(--primary-strong);
        }

        .error {
            padding: 10px 14px;
            margin-bottom: 12px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: var(--radius);
            color: #b91c1c;
            font-size: 13px;
            font-weight: 600;
        }

        .help-section {
            margin-top: 18px;
            padding: 12px 14px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: var(--radius);
            text-align: center;
            font-size: 13px;
            color: #166534;
        }

        .help-section a {
            color: #15803d;
            text-decoration: none;
            font-weight: 700;
        }

        .help-section a:hover { text-decoration: underline; }

        .link {
            text-align: center;
            margin-top: 16px;
            color: var(--muted);
            font-size: 13px;
        }

        .link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
        }

        .link a:hover { color: var(--primary-strong); }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 18px;
            z-index: 9999;
        }

        .modal {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .modal-header {
            padding: 14px 16px;
            background: #fef2f2;
            border-bottom: 1px solid #fecaca;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-header .title {
            font-weight: 800;
            color: #b91c1c;
        }

        .modal-body {
            padding: 14px 16px;
            color: #1f2937;
            font-weight: 600;
            font-size: 14px;
        }

        .modal-footer {
            padding: 14px 16px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            border-top: 1px solid var(--border);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            padding: 8px 14px;
            border-radius: var(--radius);
            border: 1px solid transparent;
            cursor: pointer;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-strong);
            border-color: var(--primary-strong);
        }

        .btn-danger {
            background: #fef2f2;
            border-color: #fecaca;
            color: #b91c1c;
        }

        .btn-danger:hover {
            background: #fee2e2;
            border-color: #fca5a5;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-brand">
            <span class="login-brand__icon" aria-hidden="true">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
            </span>
            <span class="login-brand__title">LaundroDesk</span>
        </div>

        <div class="login-info">
            <strong>Masuk untuk semua pengguna.</strong><br>
            Admin, kasir, manager, dan pelanggan bisa menggunakan halaman ini.
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="error">{{ $error }}</div>
            @endforeach
        @endif

        @if (session('message'))
            <input type="hidden" id="login-modal-message" value="{{ session('message') }}">
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" required value="{{ old('email') }}" placeholder="username">
                <div class="help-text">Tidak perlu mengetik <strong>@mail.com</strong>; akan ditambahkan otomatis jika tidak ada domain.</div>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>

        <div class="help-section">
            Lupa password atau tidak bisa login? <a href="https://wa.me/6282133073785?text=Saya%20lupa%20password%20atau%20tidak%20bisa%20login" target="_blank">Hubungi Admin via WhatsApp</a>
        </div>

        <div class="link">
            Belum punya akun? <a href="/register">Daftar di sini</a>
        </div>
    </div>

    <div class="modal-overlay" id="loginModalOverlay" role="dialog" aria-modal="true" aria-labelledby="loginModalTitle">
        <div class="modal">
            <div class="modal-header">
                <span style="font-size:18px;">⛔</span>
                <div class="title" id="loginModalTitle">Akses ditolak</div>
            </div>
            <div class="modal-body" id="loginModalBody">Pelanggan harus daftar dahulu</div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="button" onclick="closeLoginModal()">Tutup</button>
                <a class="btn btn-primary" href="/register">Daftar</a>
            </div>
        </div>
    </div>

    <script>
        (function(){
            const domain = '@mail.com';
            const emailInput = document.getElementById('email');
            const form = emailInput && emailInput.closest('form');

            function normalizeEmail() {
                if (!emailInput) return;
                const val = emailInput.value.trim();
                if (!val) return;
                if (val.indexOf('@') === -1) {
                    emailInput.value = val + domain;
                }
            }

            if (emailInput) {
                emailInput.addEventListener('blur', normalizeEmail);
            }

            if (form) {
                form.addEventListener('submit', function(){
                    normalizeEmail();
                });
            }
        })();

        const overlay = document.getElementById('loginModalOverlay');
        const body = document.getElementById('loginModalBody');
        const hidden = document.getElementById('login-modal-message');

        function closeLoginModal(){
            overlay.style.display = 'none';
        }

        if (hidden && hidden.value) {
            body.textContent = hidden.value;
            overlay.style.display = 'flex';
            overlay.addEventListener('click', function(e){
                if (e.target === overlay) closeLoginModal();
            });
        }
    </script>
</body>
</html>
