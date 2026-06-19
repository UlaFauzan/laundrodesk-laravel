<h1>Edit Transaksi</h1>

<form action="/transaksi/{{ $transaksi->id }}" method="POST">
    @csrf
    @method('PUT')

    <label>Pelanggan</label>
    <select name="pelanggan_id">

        @foreach($pelanggan as $p)
        <option value="{{ $p->id }}"
            {{ $transaksi->pelanggan_id == $p->id ? 'selected' : '' }}>
            {{ $p->nama }}
        </option>
        @endforeach

    </select>

    <br><br>

    <label>Layanan</label>
    <select name="layanan_id">

        @foreach($layanan as $l)
        <option value="{{ $l->id }}"
            {{ $transaksi->layanan_id == $l->id ? 'selected' : '' }}>
            {{ $l->nama_layanan }}
        </option>
        @endforeach

    </select>

    <br><br>

    <label>Berat KG</label>
    <input type="number"
           step="0.01"
           name="berat_kg"
           value="{{ $transaksi->berat_kg }}">

    <br><br>

    <label>Total Harga</label>
    <input type="number"
           name="total_harga"
           value="{{ $transaksi->total_harga }}">

    <br><br>

    <label>Status Laundry</label>
    <select name="status_laundry_id">
        <option value="">-- Pilih Status --</option>
        @foreach($statusLaundry as $status)
            <option value="{{ $status->id }}"
                {{ old('status_laundry_id', $transaksi->status_laundry_id) == $status->id ? 'selected' : '' }}>
                {{ $status->nama_status }}
            </option>
        @endforeach
    </select>

    <br><br>

    <label>Tanggal Masuk</label>
    <input type="date"
           name="tanggal_masuk"
           value="{{ $transaksi->tanggal_masuk }}">

    <br><br>

    <label>Tanggal Selesai</label>
    <input type="date"
           name="tanggal_selesai"
           value="{{ $transaksi->tanggal_selesai }}">

    <br><br>

    @if(! $transaksi->pembayaran || $transaksi->pembayaran->status_pembayaran !== 'lunas')
        <h2>Data Pembayaran</h2>

        <label>Jumlah Bayar</label>
        <input type="number"
               name="jumlah_bayar"
               value="{{ old('jumlah_bayar', $transaksi->pembayaran->jumlah_bayar ?? '') }}"
               min="0">

        <br><br>

        <label>Metode Pembayaran</label>
        <select name="metode_pembayaran">
            <option value="">-- Pilih metode --</option>
            <option value="tunai" {{ old('metode_pembayaran', $transaksi->pembayaran->metode_pembayaran ?? '') == 'tunai' ? 'selected' : '' }}>Tunai</option>
            <option value="qris" {{ old('metode_pembayaran', $transaksi->pembayaran->metode_pembayaran ?? '') == 'qris' ? 'selected' : '' }}>QRIS</option>
        </select>

        <br><br>

        @if($transaksi->pembayaran)
            <p>Status saat ini: <strong>{{ $transaksi->pembayaran->status_pembayaran }}</strong></p>
            <p>Sisa: <strong>Rp{{ number_format(max(0, $transaksi->total_harga - $transaksi->pembayaran->jumlah_bayar), 0, ',', '.') }}</strong></p>
            <br><br>
        @endif
    @endif

    <button type="submit">
        Update
    </button>
</form>
