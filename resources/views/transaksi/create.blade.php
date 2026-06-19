<h1>Tambah Transaksi</h1>

<form action="/transaksi" method="POST">
    @csrf

    <h3>Pelanggan</h3>

    <div style="margin-bottom:12px;">
        <label style="display:block; margin-bottom:4px; font-weight:bold; font-size:13px;">Pilih Pelanggan Lama</label>
        <select name="pelanggan_id" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            <option value="">-- Pelanggan baru --</option>
            @foreach($pelanggan as $p)
                <option value="{{ $p->id }}" {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>
                    {{ $p->nama }} - {{ $p->telepon }}
                </option>
            @endforeach
        </select>
    </div>

    <p style="margin:12px 0; font-style:italic; font-size:12px;">Jika pelanggan baru, isi data berikut:</p>

    <div style="margin-bottom:12px;">
        <label style="display:block; margin-bottom:4px; font-weight:bold; font-size:13px;">Nama Pelanggan</label>
        <input type="text" name="customer_name" value="{{ old('customer_name') }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
    </div>

    <div style="margin-bottom:12px;">
        <label style="display:block; margin-bottom:4px; font-weight:bold; font-size:13px;">Email Pelanggan</label>
        <input type="email" name="customer_email" value="{{ old('customer_email') }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
    </div>

    <div style="margin-bottom:12px;">
        <label style="display:block; margin-bottom:4px; font-weight:bold; font-size:13px;">Telepon Pelanggan</label>
        <input type="text" name="customer_telepon" value="{{ old('customer_telepon') }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
    </div>

    <div style="margin-bottom:12px;">
        <label style="display:block; margin-bottom:4px; font-weight:bold; font-size:13px;">Alamat Pelanggan</label>
        <input type="text" name="customer_alamat" value="{{ old('customer_alamat') }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
    </div>

    <h3>Transaksi</h3>

    <div style="margin-bottom:12px;">
        <label style="display:block; margin-bottom:4px; font-weight:bold; font-size:13px;">Layanan</label>
        <select name="layanan_id" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            <option value="">-- Pilih layanan --</option>
            @foreach($layanan as $l)
                <option value="{{ $l->id }}" {{ old('layanan_id') == $l->id ? 'selected' : '' }}>
                    {{ $l->nama_layanan }} - Rp{{ number_format($l->harga_per_kg, 0, ',', '.') }}/kg
                </option>
            @endforeach
        </select>
    </div>

    <div style="margin-bottom:12px;">
        <label style="display:block; margin-bottom:4px; font-weight:bold; font-size:13px;">Berat (KG)</label>
        <input type="number" step="0.01" min="0.1" name="berat_kg" value="{{ old('berat_kg') }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
    </div>

    <div style="margin-bottom:12px;">
        <label style="display:block; margin-bottom:4px; font-weight:bold; font-size:13px;">Total Harga</label>
        <input type="number" min="0" name="total_harga" value="{{ old('total_harga') }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
    </div>

    <div style="margin-bottom:12px;">
        <label style="display:block; margin-bottom:4px; font-weight:bold; font-size:13px;">Status Laundry</label>
        <select name="status_laundry_id" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            <option value="">-- Pilih Status --</option>
            @foreach($statusLaundry as $status)
                <option value="{{ $status->id }}" {{ old('status_laundry_id') == $status->id ? 'selected' : '' }}>
                    {{ $status->nama_status }}
                </option>
            @endforeach
        </select>
    </div>

    <div style="margin-bottom:12px;">
        <label style="display:block; margin-bottom:4px; font-weight:bold; font-size:13px;">Tanggal Masuk</label>
        <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', now()->toDateString()) }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
    </div>

    <button type="submit" style="padding:10px 20px; background:#7c3aed; color:white; border:none; border-radius:4px; font-weight:bold; cursor:pointer;">
        Simpan
    </button>
</form>
