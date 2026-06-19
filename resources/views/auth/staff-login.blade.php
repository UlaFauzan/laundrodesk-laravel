<!DOCTYPE html>
<html>
<head>
    <title>Staff Login - Laundry Management</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Login Staf</h1>

        <div class="info">
            <strong>Login untuk admin, kasir, dan manager.</strong><br>
            Halaman ini khusus untuk staf dan manajemen. Akun pelanggan tidak dapat masuk di sini.
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="error">{{ $error }}</div>
            @endforeach
        @endif

        @if (session('message'))
            <div class="error" style="background: rgba(34,197,94,.12); color: #166534; border-color: rgba(34,197,94,.25);">
                {{ session('message') }}
            </div>
        @endif

        <form method="POST" action="{{ route('staff.login') }}">
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
                    form.addEventListener('submit', function(e){
                        normalizeEmail();
                    });
                }
            })();
        </script>

        <div class="link">
            Bukan staf? <a href="/login">Login pelanggan</a>
        </div>
    </div>
</body>
</html>