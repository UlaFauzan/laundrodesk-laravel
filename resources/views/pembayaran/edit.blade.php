<h1>Edit Pembayaran</h1>

@if ($errors->any())
    <div style="color: red; margin-bottom: 16px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="/pembayaran/{{ $pembayaran->id }}" method="POST">
    @csrf
    @method('PUT')

    <p><strong>ID Transaksi:</strong> #{{ $pembayaran->transaksi->id }}</p>
    <p><strong>Pelanggan:</strong> {{ $pembayaran->transaksi->pelanggan->nama }}</p>
    <p><strong>Layanan:</strong> {{ $pembayaran->transaksi->layanan->nama_layanan }}</p>
    <p><strong>Total Harga:</strong> Rp{{ number_format($pembayaran->transaksi->total_harga, 0, ',', '.') }}</p>
    <p><strong>Sudah Dibayar:</strong> Rp{{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</p>
    <p><strong>Sisa Hutang:</strong> Rp{{ number_format($sisa, 0, ',', '.') }}</p>

    <input type="hidden" name="transaksi_id" value="{{ $pembayaran->transaksi_id }}">

    <label>Bayar Tambahan</label>
    <input
        id="jumlah_bayar"
        type="number"
        name="jumlah_bayar"
        min="1"
        max="{{ $sisa }}"
        value="{{ old('jumlah_bayar', $sisa) }}"
        required
    >

    <br><br>

    <label>Metode Pembayaran</label>
    <select id="metode_pembayaran" name="metode_pembayaran" required>
        <option value="">Pilih metode</option>
        <option value="tunai" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'tunai' ? 'selected' : '' }}>Tunai</option>
        <option value="qris" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'qris' ? 'selected' : '' }}>QRIS</option>
    </select>

    <br><br>

    <button type="submit">
        Update Pembayaran
    </button>
</form>
