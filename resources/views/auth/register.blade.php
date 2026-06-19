<!DOCTYPE html>
<html>
<head>
    <title>Register - Laundry Management</title>
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
        }

        body { font-family: 'Merriweather', Georgia, 'Times New Roman', Times, serif; line-height: 1.6; background: var(--bg); color: var(--text); min-height: 100vh; }

        .container {
            max-width: 430px;
            margin: 50px auto;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Register</h1>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="error">{{ $error }}</div>
            @endforeach
        @endif

        <form method="POST" action="/register">
            @csrf
            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" id="name" name="name" required value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <div style="display:flex; gap:10px; align-items:center;">
                    <input
                        type="text"
                        id="email_prefix"
                        name="email_prefix"
                        required
                        placeholder="contoh: nama"
                        value="{{ old('email_prefix') }}"
                        style="flex:1;"
                    >
                    <span style="padding: 0 6px; font-weight:900; color: var(--primary);">@mail.com</span>
                </div>
                <input type="hidden" name="email" id="email" value="{{ old('email', '') }}">


                <script>
                    (function () {
                        const prefixInput = document.getElementById('email_prefix');
                        const emailHidden = document.getElementById('email');
                        if (!prefixInput || !emailHidden) return;

                        const sync = () => {
                            const p = (prefixInput.value || '').trim();
                            emailHidden.value = p ? (p + '@mail.com') : '';
                        };

                        prefixInput.addEventListener('input', sync);
                        // initial sync
                        sync();
                    })();
                </script>

            </div>

            <div class="form-group">
                <label for="telepon">Telepon</label>
                <input type="text" id="telepon" name="telepon" required value="{{ old('telepon') }}">
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <input type="text" id="alamat" name="alamat" required value="{{ old('alamat') }}">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit">Daftar</button>
        </form>

        <div class="link">
            Sudah punya akun? <a href="/login">Login di sini</a>
        </div>
    </div>
</body>
</html>
