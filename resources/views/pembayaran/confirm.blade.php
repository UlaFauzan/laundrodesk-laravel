<h1>Konfirmasi Pembayaran</h1>

@php
    $amount = request('amount', $pembayaran->jumlah_bayar);
    $payload = url('/pembayaran/complete/' . $pembayaran->id . '/' . $pembayaran->qr_token) . '?amount=' . $amount;
@endphp

<p>Transaksi: #{{ $pembayaran->transaksi_id }} — Bayar Tambahan: Rp{{ number_format($amount,0,',','.') }}</p>
<p>Metode: {{ $pembayaran->metode_pembayaran }}</p>

<p>Silakan pelanggan scan QR berikut untuk menyelesaikan pembayaran.</p>

<img
    id="qr-image"
    src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($payload) }}"
    alt="QR Code Pembayaran"
    width="220"
    height="220"
>
<div id="qrcode" style="margin-top: 12px;"></div>
<p id="qr-text">{{ $payload }}</p>
<p id="payment-status" style="margin-top: 16px;"></p>

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

    // Tetap di halaman selama 5 detik, lalu alihkan ke halaman Transaksi
    startRedirect('Silakan selesaikan pembayaran. Halaman akan beralih ke Transaksi dalam 5 detik...');

    const checkStatus = async () => {
        try {
            const res = await fetch('/pembayaran/status/{{ $pembayaran->id }}', { credentials: 'same-origin' });
            if (!res.ok) return; // maybe redirected to login

            const text = await res.text();
            let json = null;
            try { json = JSON.parse(text); } catch (e) { return; }

            if (json && json.status !== 'pending') {
                // Jika pembayaran selesai lebih dulu, langsung alihkan tanpa menunggu lagi
                clearTimeout(redirectTimeout);
                redirectTimeout = null;
                window.location.href = redirectTarget;
            }
        } catch (e) {
            console.error(e);
        }
    };

    const pollInterval = setInterval(checkStatus, 2000);
</script>
