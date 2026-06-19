<h2>Tambah Status Laundry</h2>

<div class="page-card">
    <form action="/status-laundry" method="POST">
        @csrf

        <div style="margin-bottom: 14px;">
            <label>Status</label>
            <input type="text" name="nama_status" style="width: 100%;" required>
        </div>

        <button type="submit" class="btn btn-primary" style="width: auto;">Simpan</button>
        <a href="/status-laundry" class="btn btn-primary" style="width: auto;">Kembali</a>
    </form>
</div>
