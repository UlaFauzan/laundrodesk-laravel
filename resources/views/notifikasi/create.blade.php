<h1>Tambah Notifikasi</h1>

<form action="/notifikasi" method="POST">
    @csrf

    <label>Pelanggan</label>

    <select name="pelanggan_id">
        @foreach($pelanggan as $p)
            <option value="{{ $p->id }}">
                {{ $p->nama }}
            </option>
        @endforeach
    </select>

    <br><br>

    <label>Pesan</label>

    <textarea name="pesan"></textarea>

    <br><br>

    <label>Status</label>

    <select name="status_baca">
        <option>Belum Dibaca</option>
        <option>Sudah Dibaca</option>
    </select>

    <br><br>

    <button type="submit">
        Simpan
    </button>
</form>