<h1>Tambah Log Aktivitas</h1>

<form action="/log-aktivitas" method="POST">
    @csrf

    <label>Nama Pengguna</label>
    <input type="text" name="nama_pengguna">

    <label>Aktivitas</label>
    <input type="text" name="aktivitas">

    <button type="submit">
        Simpan
    </button>
</form>