@extends('layout')

@section('title', 'Pembayaran Diterima')

@section('content')
    <div class="form-card">
        <h1>Terima kasih</h1>
        <p>Pembayaran untuk transaksi #{{ $pembayaran->transaksi_id }} telah diterima dan otomatis diperbarui.</p>
        <p>Anda akan dialihkan ke Dashboard Kasir dalam <span id="countdown">5</span> detik.</p>

        <a href="/kasir/dashboard" class="btn btn-primary">Ke Dashboard Kasir</a>
    </div>
@endsection

@push('scripts')
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
@endpush
