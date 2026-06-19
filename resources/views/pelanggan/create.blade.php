<h1>Tambah Pelanggan</h1>

<form action="/pelanggan" method="POST" style="max-width:500px;">
    @csrf

    <div style="margin-bottom:12px;">
        <label style="display:block; margin-bottom:4px; font-weight:bold; font-size:13px;">Nama</label>
        <input type="text" name="nama" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
    </div>

    <div style="margin-bottom:12px;">
        <label style="display:block; margin-bottom:4px; font-weight:bold; font-size:13px;">Telepon</label>
        <input type="text" name="telepon" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
    </div>

    <div style="margin-bottom:12px;">
        <label style="display:block; margin-bottom:4px; font-weight:bold; font-size:13px;">Alamat</label>
        <textarea name="alamat" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;"></textarea>
    </div>

    <button type="submit" style="padding:10px 20px; background:#7c3aed; color:white; border:none; border-radius:4px; font-weight:bold; cursor:pointer;">
        Simpan
    </button>
</form>