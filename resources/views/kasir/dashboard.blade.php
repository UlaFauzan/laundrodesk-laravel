<h1>Dashboard Kasir - Data Pelanggan</h1>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Telepon</th>
        <th>Alamat</th>
        <th>Aksi</th>
    </tr>
    @foreach($pelanggan as $p)
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
        <td>
            @if($hutangPembayaran)
                <a href="/pembayaran/{{ $hutangPembayaran->pembayaran->id }}/edit">Bayar Hutang</a>
            @else
                -
            @endif
        </td>
    </tr>
    @endforeach
</table>

<script>
    // Poll for unnotified pembayaran that changed to 'lunas' or 'hutang'
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

                // mark as notified to avoid duplicate alerts
                await fetch('/pembayaran/notify/' + item.id, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    },
                    credentials: 'same-origin'
                });

                // redirect to pelanggan dashboard (list)
                window.location.href = '/kasir/dashboard';
            }
        } catch (e) {
            console.error('notification check failed', e);
        }
    }

    // start polling every 2 seconds
    setInterval(checkNotifications, 2000);
</script>