<h2>Edit Layanan</h2>

<div class="page-card">
    <form action="/layanan/{{ $layanan->id }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 14px;">
            <label>Nama Layanan</label>
            <input type="text" name="nama_layanan" value="{{ $layanan->nama_layanan }}" style="width: 100%;" required>
        </div>

        <div style="margin-bottom: 14px;">
            <label>Harga per Kg</label>
            <input type="number" name="harga_per_kg" value="{{ $layanan->harga_per_kg }}" style="width: 100%;" required>
        </div>

        <div style="margin-bottom: 14px;">
            <label>Deskripsi</label>
            <textarea name="deskripsi" style="width: 100%;" required>{{ $layanan->deskripsi }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="width: auto;">Update</button>
        <a href="/layanan" class="btn btn-primary" style="width: auto;">Kembali</a>
    </form>
</div>
