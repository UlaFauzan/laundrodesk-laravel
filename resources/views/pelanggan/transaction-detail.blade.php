@extends('layout')

@section('content')
    <h2>Detail Transaksi</h2>

    <div class="card" style="padding: 15px; max-width: 800px;">
        <p><strong>Kode:</strong> {{ $transaksi->kode_transaksi ?? $transaksi->transaksi_id }}</p>
        <p><strong>Layanan:</strong> {{ $transaksi->layanan?->nama_layanan ?? '-' }}</p>
        <p><strong>Berat (kg):</strong> {{ $transaksi->total_berat }}</p>
        <p><strong>Total Harga:</strong> {{ number_format($transaksi->total_harga,0,',','.') }}</p>
        <p><strong>Status Laundry:</strong> {{ $transaksi->statusLaundry?->nama_status ?? $transaksi->status_laundry ?? 'Dalam proses' }}</p>
        <p><strong>Status Pembayaran:</strong> {{ $transaksi->pembayaran?->status ?? $transaksi->status_pembayaran ?? 'Belum' }}</p>
        @if($transaksi->pembayaran)
            <p><strong>Jumlah Bayar:</strong> {{ number_format($transaksi->pembayaran->jumlah_bayar,0,',','.') }}</p>
        @endif

        <a href="{{ route('pelanggan.transactions') }}" class="btn btn-secondary">Kembali</a>
        <button type="button" class="btn btn-danger" onclick="document.getElementById('report-modal').style.display='block'">🆘 Laporkan Masalah</button>
    </div>

    {{-- Modal laporan masalah --}}
    <div id="report-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:100; align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:18px; padding:22px; width:90%; max-width:480px; box-shadow:0 20px 50px rgba(0,0,0,.2);">
            <h3 style="margin-bottom:12px;">Laporkan Masalah</h3>
            <form id="report-form">
                @csrf
                <input type="hidden" name="page_name" value="Detail Transaksi #{{ $transaksi->kode_transaksi ?? $transaksi->id }}">
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
@endsection
