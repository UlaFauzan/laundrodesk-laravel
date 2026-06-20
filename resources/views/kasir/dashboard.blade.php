@extends('layout')

@section('title', 'Dashboard Kasir')

@section('content')
    <div class="page-header">
        <div>
            <h1>Dashboard Kasir</h1>
            <p>Data pelanggan dan status hutang pembayaran yang perlu ditindaklanjuti.</p>
        </div>
        <a href="/transaksi/create" class="btn btn-success">Tambah Transaksi</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th>Poin</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pelanggan as $p)
                @php
                    $hutangPembayaran = $p->transaksi->first(function ($t) {
                        return $t->pembayaran && $t->pembayaran->status_pembayaran === 'hutang';
                    });
                @endphp
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->telepon }}</td>
                    <td>{{ $p->alamat }}</td>
                    <td>{{ $p->poin ?? 0 }}</td>
                    <td>
                        @if($hutangPembayaran)
                            <a href="/pembayaran/{{ $hutangPembayaran->pembayaran->id }}/edit" class="btn btn-secondary">Bayar Hutang</a>
                        @else
                            <span class="text-muted">Tidak ada hutang</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="table-empty">Belum ada pelanggan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

@push('scripts')
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')
            ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            : null;

        async function checkNotifications() {
            try {
                const res = await fetch('/pembayaran/notifications', { credentials: 'same-origin' });
                if (!res.ok) return;
                const json = await res.json();
                if (json.items && json.items.length > 0) {
                    const item = json.items[0];
                    const nama = item.transaksi && item.transaksi.pelanggan ? item.transaksi.pelanggan.nama : 'Pelanggan';
                    const statusLabel = item.status_pembayaran === 'lunas' ? 'lunas' : 'sebagian (hutang)';
                    alert(nama + ' sudah melakukan scan pembayaran. Status: ' + statusLabel + '. Mengarahkan ke dashboard pelanggan...');

                    await fetch('/pembayaran/notify/' + item.id, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || ''
                        },
                        credentials: 'same-origin'
                    });

                    window.location.href = '/kasir/dashboard';
                }
            } catch (e) {
                console.error('notification check failed', e);
            }
        }

        setInterval(checkNotifications, 2000);
    </script>
@endpush
