@extends('layout')

@section('title', 'Edit Pembayaran')

@section('content')
    <div class="page-header">
        <div>
            <h1>Edit Pembayaran</h1>
            <p>Tambahkan pembayaran untuk melunasi sisa hutang transaksi.</p>
        </div>
    </div>

    <form class="form-card" action="/pembayaran/{{ $pembayaran->id }}" method="POST">
        @csrf
        @method('PUT')

        <dl class="detail-list">
            <div class="detail-row">
                <dt>ID Transaksi</dt>
                <dd>#{{ $pembayaran->transaksi->id }}</dd>
            </div>
            <div class="detail-row">
                <dt>Pelanggan</dt>
                <dd>{{ $pembayaran->transaksi->pelanggan->nama }}</dd>
            </div>
            <div class="detail-row">
                <dt>Layanan</dt>
                <dd>{{ $pembayaran->transaksi->layanan->nama_layanan }}</dd>
            </div>
            <div class="detail-row">
                <dt>Total Harga</dt>
                <dd>Rp{{ number_format($pembayaran->transaksi->total_harga, 0, ',', '.') }}</dd>
            </div>
            <div class="detail-row">
                <dt>Sudah Dibayar</dt>
                <dd>Rp{{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</dd>
            </div>
            <div class="detail-row">
                <dt>Sisa Hutang</dt>
                <dd>Rp{{ number_format($sisa, 0, ',', '.') }}</dd>
            </div>
        </dl>

        <input type="hidden" name="transaksi_id" value="{{ $pembayaran->transaksi_id }}">

        <div class="form-grid">
            <div class="field">
                <label for="jumlah_bayar">Bayar Tambahan</label>
                <input
                    id="jumlah_bayar"
                    type="number"
                    name="jumlah_bayar"
                    min="1"
                    max="{{ $sisa }}"
                    value="{{ old('jumlah_bayar', $sisa) }}"
                    required
                >
            </div>

            <div class="field">
                <label for="metode_pembayaran">Metode Pembayaran</label>
                <select id="metode_pembayaran" name="metode_pembayaran" required>
                    <option value="">Pilih metode</option>
                    <option value="tunai" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'tunai' ? 'selected' : '' }}>Tunai</option>
                    <option value="qris" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-primary">Update Pembayaran</button>
            <a href="/pembayaran" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
@endsection
