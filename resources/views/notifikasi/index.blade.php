<h1>Data Notifikasi</h1>

<a href="/notifikasi/create">
    Tambah Notifikasi
</a>

<table border="1">
    <tr>
        <th>ID</th>
        <th>ID Pelanggan</th>
        <th>Pesan</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    @foreach($notifikasi as $n)
    <tr>
        <td>{{ $n->id }}</td>
        <td>{{ $n->pelanggan_id }}</td>
        <td>{{ $n->pesan }}</td>
        <td>{{ $n->status_baca }}</td>

        <td>
            <a href="/notifikasi/{{ $n->id }}/edit">
                Edit
            </a>

            <form action="/notifikasi/{{ $n->id }}" method="POST">
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