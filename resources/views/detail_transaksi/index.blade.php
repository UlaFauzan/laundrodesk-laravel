<h1>Data Detail Transaksi</h1>

<a href="/detail-transaksi/create">
    Tambah Detail Transaksi
</a>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Transaksi</th>
        <th>Layanan</th>
        <th>Berat (Kg)</th>
        <th>Subtotal</th>
        <th>Aksi</th>
    </tr>

    @foreach($detail as $d)
    <tr>
        <td>{{ $d->id }}</td>
        <td>{{ $d->transaksi_id }}</td>
        <td>{{ $d->layanan_id }}</td>
        <td>{{ $d->berat_kg }}</td>
        <td>{{ $d->subtotal }}</td>

        <td>
            <a href="/detail-transaksi/{{ $d->id }}/edit">
                Edit
            </a>

            <form action="/detail-transaksi/{{ $d->id }}" method="POST">
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