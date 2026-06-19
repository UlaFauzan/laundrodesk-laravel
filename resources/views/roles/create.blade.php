<h2>Tambah Role</h2>

<div class="page-card">
    <form action="/roles" method="POST">
        @csrf

        <div style="margin-bottom: 14px;">
            <label>Nama Role</label>
            <input type="text" name="nama_role" style="width: 100%;" required>
        </div>

        <button type="submit" class="btn btn-primary" style="width: auto;">Simpan</button>
        <a href="/roles" class="btn btn-primary" style="width: auto;">Kembali</a>
    </form>
</div>
