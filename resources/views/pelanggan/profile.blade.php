@extends('layout')

@section('content')
    <h2>Profil Pelanggan</h2>
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px; padding: 15px; background: #d4edda; border: 1px solid #c3e6cb; color: #155724;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card" style="padding: 20px; max-width: 600px;">
        <div style="display:flex; align-items:center; gap:16px; margin-bottom: 14px;">
            @php
                $avatarUrl = auth()->user()->avatar_url ?? null;
            @endphp
            <img
                src="{{ $avatarUrl ?: 'https://ui-avatars.com/api/?name=' . urlencode($user->pelanggan?->nama ?? $user->name) . '&size=128' }}"
                alt="Avatar Pelanggan"
                width="72"
                height="72"
                style="border-radius: 50%; border: 2px solid rgba(124,58,237,.25); background: rgba(124,58,237,.06); object-fit: cover;"
            >
            <div>
                <div style="font-weight: 900; font-size: 18px; line-height: 1.2;">{{ $user->pelanggan?->nama ?? $user->name }}</div>
                <div style="color: var(--muted); font-weight: 700; font-size: 13px;">{{ $user->role->nama_role }}</div>
            </div>
        </div>

        <div class="mb-3"><strong>Email:</strong> {{ $user->email }}</div>
        <div class="mb-3"><strong>Telepon:</strong> {{ $user->pelanggan?->telepon ?? 'Belum diisi' }}</div>
        <div class="mb-3"><strong>Alamat:</strong> {{ $user->pelanggan?->alamat ?? 'Belum diisi' }}</div>
        <div class="mb-3"><strong>Poin Loyalty:</strong> {{ $user->pelanggan?->poin ?? 0 }}</div>

        @if(isset($notifications) && $notifications->isNotEmpty())
        <div class="alert alert-info" style="margin-bottom:20px; padding:16px; border-radius:12px; background:#eff6ff; border:1px solid #bfdbfe; color:#1d4ed8;">
            <strong>Notifikasi baru:</strong> Anda memiliki {{ $notifications->count() }} notifikasi belum dibaca.
            <div style="margin-top:10px;">
                <a href="{{ route('pelanggan.notifications') }}" class="btn btn-primary">Lihat Notifikasi</a>
            </div>
        </div>
    @endif

    <div style="display:flex; flex-wrap:wrap; gap:10px;">
            <a href="{{ route('pelanggan.profile.edit') }}" class="btn btn-primary">Edit Profil</a>
            <a href="{{ route('pelanggan.points') }}" class="btn btn-success">Poin Loyalty</a>
            <a href="{{ route('pelanggan.status') }}" class="btn btn-success">Status Laundry</a>
            <a href="{{ route('pelanggan.transactions') }}" class="btn btn-primary">Riwayat Transaksi</a>
            <a href="{{ route('pelanggan.notifications') }}" class="btn btn-secondary">Notifikasi</a>
            <button type="button" class="btn btn-danger" onclick="document.getElementById('report-modal').style.display='block'">🆘 Laporkan Masalah</button>
        </div>

        {{-- Modal laporan masalah --}}
        <div id="report-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:100; align-items:center; justify-content:center;">
            <div style="background:#fff; border-radius:18px; padding:22px; width:90%; max-width:480px; box-shadow:0 20px 50px rgba(0,0,0,.2);">
                <h3 style="margin-bottom:12px;">Laporkan Masalah</h3>
                <form id="report-form">
                    @csrf
                    <input type="hidden" name="page_name" value="Profil Pelanggan">
                    <div style="margin-bottom:12px;">
                        <label style="display:block; font-weight:700; margin-bottom:6px;">Deskripsi masalah</label>
                        <textarea name="description" rows="4" style="width:100%; padding:10px; border:1px solid rgba(124,58,237,.25); border-radius:12px; resize:vertical;" placeholder="Jelaskan masalah yang Anda alami..." required></textarea>
                    </div>
                    <div style="display:flex; gap:10px; justify-content:flex-end;">
                        <button type="button" class="btn btn-secondary" onclick="document.getElementById('report-modal').style.display='none'" style="background:#f3f4f6; color:#374151; border-color:#d1d5db;">Batal</button>
                        <button type="submit" class="btn btn-danger">Kirim Laporan</button>
                    </div>
                </form>
                <div id="report-message" style="margin-top:12px; font-weight:700;"></div>
            </div>
        </div>

        <script>
            document.getElementById('report-form').addEventListener('submit', async function (e) {
                e.preventDefault();
                const form = e.target;
                const formData = new FormData(form);
                const messageEl = document.getElementById('report-message');

                try {
                    const response = await fetch('{{ route('error-reports.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    });

                    const data = await response.json();
                    messageEl.textContent = data.message;
                    messageEl.style.color = data.success ? '#15803d' : '#b91c1c';

                    if (data.success) {
                        form.reset();
                        setTimeout(() => {
                            document.getElementById('report-modal').style.display = 'none';
                            messageEl.textContent = '';
                        }, 2000);
                    }
                } catch (err) {
                    messageEl.textContent = 'Gagal mengirim laporan. Silakan coba lagi.';
                    messageEl.style.color = '#b91c1c';
                }
            });
        </script>
    </div>
@endsection
