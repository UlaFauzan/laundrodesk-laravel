<h1>Data Pembayaran</h1>

<a href="/pembayaran/create">
    Tambah Pembayaran
</a>

<table border="1">
    <tr>
        <th>ID</th>
        <th>ID Transaksi</th>
        <th>Jumlah Bayar</th>
        <th>Metode</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    @foreach($pembayaran as $p)
    <tr>
        <td>{{ $p->id }}</td>
        <td>{{ $p->transaksi_id }}</td>
        <td>{{ $p->jumlah_bayar }}</td>
        <td>{{ $p->metode_pembayaran }}</td>
        <td>{{ $p->status_pembayaran }}</td>

        <td>
            <a href="/pembayaran/{{ $p->id }}/edit">
                Edit
            </a>

            <form action="/pembayaran/{{ $p->id }}" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit">
                    Hapus
                </button>
            </form>
        </td>
    </tr>
    @endforeach
</table>