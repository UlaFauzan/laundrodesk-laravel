@extends('layout')

@section('title', 'Konfirmasi Pembayaran')

@section('content')
    <div class="page-header">
        <div>
            <h1>Konfirmasi Pembayaran</h1>
            <p>Tunjukkan QR berikut kepada pelanggan untuk menyelesaikan pembayaran.</p>
        </div>
    </div>

    @php
        $amount = request('amount', $pembayaran->jumlah_bayar);
        $payload = url('/pembayaran/complete/' . $pembayaran->id . '/' . $pembayaran->qr_token) . '?amount=' . $amount;
    @endphp

    <div class="form-card">
        <div class="qr-panel">
            <div class="qr-box">
                <img
                    id="qr-image"
                    src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($payload) }}"
                    alt="QR Code Pembayaran"
                    width="220"
                    height="220"
                >
                <div id="qrcode"></div>
            </div>

            <div>
                <dl class="detail-list">
                    <div class="detail-row">
                        <dt>Transaksi</dt>
                        <dd>#{{ $pembayaran->transaksi_id }}</dd>
                    </div>
                    <div class="detail-row">
                        <dt>Bayar Tambahan</dt>
                        <dd>Rp{{ number_format($amount, 0, ',', '.') }}</dd>
                    </div>
                    <div class="detail-row">
                        <dt>Metode</dt>
                        <dd>{{ $pembayaran->metode_pembayaran }}</dd>
                    </div>
                </dl>

                <p id="payment-status" class="alert alert-info"></p>
                <p id="qr-text" class="code-text">{{ $payload }}</p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        const payload = @json($payload);
        if (window.QRCode) {
            document.getElementById('qr-image').style.display = 'none';
            new QRCode(document.getElementById('qrcode'), { text: payload, width: 220, height: 220 });
        }
        document.getElementById('qr-text').textContent = payload;

        const paymentStatus = document.getElementById('payment-status');
        let redirectTimeout = null;
        const redirectTarget = '/transaksi';

        const startRedirect = (message) => {
            paymentStatus.textContent = message;
            if (!redirectTimeout) {
                redirectTimeout = setTimeout(() => {
                    window.location.href = redirectTarget;
                }, 5000);
            }
        };

        startRedirect('Silakan selesaikan pembayaran. Halaman akan beralih ke Transaksi dalam 5 detik...');

        const checkStatus = async () => {
            try {
                const res = await fetch('/pembayaran/status/{{ $pembayaran->id }}', { credentials: 'same-origin' });
                if (!res.ok) return;

                const text = await res.text();
                let json = null;
                try { json = JSON.parse(text); } catch (e) { return; }

                if (json && json.status !== 'pending') {
                    clearTimeout(redirectTimeout);
                    redirectTimeout = null;
                    window.location.href = redirectTarget;
                }
            } catch (e) {
                console.error(e);
            }
        };

        setInterval(checkStatus, 2000);
    </script>
@endpush
