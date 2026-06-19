<!DOCTYPE html>
<html>
<head>
    <title>Login - Laundry Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root{
            --bg: #f6f5ff;
            --card: rgba(255,255,255,.85);
            --text: #1f1b2e;
            --muted: #6b6790;
            --primary: #7c3aed;
            --border: rgba(124,58,237,.18);
            --success: #22c55e;
            --danger: #ef4444;
        }

        body { font-family: 'Merriweather', Georgia, 'Times New Roman', Times, serif; line-height: 1.6; background: var(--bg); color: var(--text); min-height: 100vh; }

        .container {
            max-width: 430px;
            margin: 90px auto;
            background: var(--card);
            padding: 26px;
            border-radius: 18px;
            border: 1px solid var(--border);
            box-shadow: 0 14px 40px rgba(124,58,237,.08);
            backdrop-filter: blur(10px);
        }

        h1 { text-align: center; margin-bottom: 22px; color: var(--primary); font-size: 22px; font-weight: 900; }
        .form-group { margin-bottom: 14px; }
        label { display: block; margin-bottom: 6px; color: var(--muted); font-weight: 800; font-size: 13px; }
        input {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid rgba(124,58,237,.18);
            border-radius: 12px;
            font-size: 14px;
            background: rgba(255,255,255,.7);
        }

        input:focus {
            outline: none;
            border-color: rgba(124,58,237,.45);
            box-shadow: 0 0 0 4px rgba(124,58,237,.12);
        }

        button {
            width: 100%;
            padding: 11px 12px;
            background: rgba(124,58,237,.12);
            color: var(--primary);
            border: 1px solid rgba(124,58,237,.25);
            border-radius: 12px;
            font-size: 16px;
            font-weight: 900;
            cursor: pointer;
            margin-top: 6px;
        }
        button:hover { background: rgba(124,58,237,.18); }

        .link { text-align: center; margin-top: 14px; }
        .link a { color: var(--primary); text-decoration: none; font-weight: 900; }
        .error { color: #b91c1c; font-size: 14px; margin-top: 6px; font-weight: 800; }
        .info {
            background: rgba(167,139,250,.12);
            color: var(--text);
            border: 1px solid rgba(124,58,237,.25);
            padding: 14px;
            border-radius: 14px;
            margin-bottom: 18px;
            font-size: 13px;
            line-height: 1.45;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
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
            border-radius: 16px;
            border: 1px solid rgba(124,58,237,.2);
            box-shadow: 0 20px 60px rgba(0,0,0,.2);
            overflow: hidden;
        }

        .modal-header {
            padding: 14px 16px;
            background: rgba(239,68,68,.08);
            border-bottom: 1px solid rgba(239,68,68,.15);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-header .title {
            font-weight: 900;
            color: #b91c1c;
        }

        .modal-body {
            padding: 14px 16px;
            color: #1f2937;
            font-weight: 700;
        }

        .modal-footer {
            padding: 14px 16px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            border-top: 1px solid rgba(124,58,237,.12);
        }

        .btn {
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid rgba(124,58,237,.25);
            cursor: pointer;
            font-weight: 900;
        }

        .btn-primary {
            background: rgba(124,58,237,.12);
            color: var(--primary);
        }

        .btn-primary:hover { background: rgba(124,58,237,.18); }

        .btn-danger {
            background: rgba(239,68,68,.12);
            border-color: rgba(239,68,68,.25);
            color: #b91c1c;
        }

        .help-section {
            margin-top: 18px;
            padding: 12px 14px;
            background: rgba(34,197,94,.08);
            border: 1px solid rgba(34,197,94,.2);
            border-radius: 12px;
            text-align: center;
            font-size: 13px;
        }

        .help-section a {
            color: #16a34a;
            text-decoration: none;
            font-weight: 900;
            transition: color 0.2s;
        }

        .help-section a:hover {
            color: #15803d;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>

        <div class="info">
            <strong>Masuk untuk semua pengguna.</strong><br>
            Admin, kasir, manager, dan pelanggan bisa menggunakan halaman ini.
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="error">{{ $error }}</div>
            @endforeach
        @endif

        @if (session('message'))
            {{-- Untuk modal/pop-up --}}
            <input type="hidden" id="login-modal-message" value="{{ session('message') }}">
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" required value="{{ old('email') }}" placeholder="username">
                <div style="font-size:12px; color:var(--muted); margin-top:6px;">Tidak perlu mengetik <strong>@mail.com</strong>; akan ditambahkan otomatis jika tidak ada domain.</div>
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

    {{-- Modal pop-up saat pelanggan belum daftar --}}
    <div class="modal-overlay" id="loginModalOverlay" role="dialog" aria-modal="true" aria-labelledby="loginModalTitle">
        <div class="modal">
            <div class="modal-header">
                <span style="font-size:18px;">⛔</span>
                <div class="title" id="loginModalTitle">Akses ditolak</div>
            </div>
            <div class="modal-body" id="loginModalBody">Pelanggan harus daftar dahulu</div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="button" onclick="closeLoginModal()">Tutup</button>
                <a class="btn btn-primary" href="/register" style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">
                    Daftar
                </a>
            </div>
        </div>
    </div>

    <script>
        // normalisasi email
        (function(){
            const domain = '@mail.com';
            const emailInput = document.getElementById('email');
            const form = emailInput && emailInput.closest('form');

            function normalizeEmail() {
                if (!emailInput) return;
                const val = emailInput.value.trim();
                if (!val) return;
                // if already contains @ dengan domain, keep as-is
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
            // auto open modal tanpa mengganggu
            // close on click outside
            overlay.addEventListener('click', function(e){
                if (e.target === overlay) closeLoginModal();
            });
        }
    </script>
</body>
</html>

