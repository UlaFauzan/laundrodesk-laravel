<h1>Terima kasih</h1>
<p>Pembayaran untuk transaksi #{{ $pembayaran->transaksi_id }} telah diterima.</p>
<p>Pembayaran untuk transaksi #{{ $pembayaran->transaksi_id }} telah diterima dan otomatis ditandai lunas.</p>
<p>Anda akan dialihkan ke Data Pelanggan (dashboard kasir) dalam <span id="countdown">5</span> detik.</p>

<script>
    let seconds = 5;
    const countdown = document.getElementById('countdown');

    const interval = setInterval(() => {
        seconds -= 1;
        countdown.textContent = seconds;
        if (seconds <= 0) {
            clearInterval(interval);
            window.location.href = '/kasir/dashboard';
        }
    }, 1000);
</script>