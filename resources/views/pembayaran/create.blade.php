<h1>Tambah Pembayaran</h1>

@if ($errors->any())
    <div style="color: red; margin-bottom: 16px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="/pembayaran" method="POST">
    @csrf

    <label>ID Transaksi</label>
    @if(isset($transaksi) && $transaksi)
        <div id="selected-transaction"
            data-transaction-id="{{ $transaksi->id }}"
            data-transaction-total="{{ $transaksi->total_harga }}"
            data-transaction-customer="{{ $transaksi->pelanggan->nama }}"
        >
            <strong>#{{ $transaksi->id }}</strong> - {{ $transaksi->pelanggan->nama }} - {{ $transaksi->layanan->nama_layanan }} - Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}
            <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
        </div>
    @else
        <select id="transaksi_id" name="transaksi_id">
            <option value="">Pilih transaksi</option>
            @foreach($transaksiList as $item)
                <option
                    value="{{ $item->id }}"
                    data-transaction-total="{{ $item->total_harga }}"
                    data-transaction-customer="{{ $item->pelanggan->nama }}"
                    {{ old('transaksi_id') == $item->id ? 'selected' : '' }}
                >
                    #{{ $item->id }} - {{ $item->pelanggan->nama }} - Rp{{ number_format($item->total_harga, 0, ',', '.') }}
                </option>
            @endforeach
        </select>
    @endif

    <br><br>

    <label>Jumlah Bayar</label>
    <input id="jumlah_bayar" type="number" name="jumlah_bayar" value="{{ old('jumlah_bayar') }}">

    <br><br>

    <label>Metode Pembayaran</label>
    <select id="metode_pembayaran" name="metode_pembayaran">
        <option value="">Pilih metode</option>
        <option value="tunai" {{ old('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>Tunai</option>
        <option value="qris" {{ old('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS</option>
    </select>

    <br><br>

    <label>Status Pembayaran</label>
    <input type="text" name="status_pembayaran" value="{{ old('status_pembayaran', 'pending') }}">

    <br><br>

    <button type="submit">
        Simpan
    </button>
</form>