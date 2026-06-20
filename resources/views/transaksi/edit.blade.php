@extends('layout')

@section('title', 'Edit Transaksi')

@section('content')
    <div class="page-header">
        <div>
            <h1>Edit Transaksi</h1>
            <p>Perbarui data transaksi, status laundry, dan pembayaran.</p>
        </div>
    </div>

    <form class="form-card" action="/transaksi/{{ $transaksi->id }}" method="POST">
        @csrf
        @method('PUT')

        <h3 class="section-title">Data Transaksi</h3>

        <div class="form-grid">
            <div class="field">
                <label for="pelanggan_id">Pelanggan</label>
                <select id="pelanggan_id" name="pelanggan_id">
                    @foreach($pelanggan as $p)
                        <option value="{{ $p->id }}" {{ old('pelanggan_id', $transaksi->pelanggan_id) == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="layanan_id">Layanan</label>
                <select id="layanan_id" name="layanan_id">
                    @foreach($layanan as $l)
                        <option value="{{ $l->id }}" {{ old('layanan_id', $transaksi->layanan_id) == $l->id ? 'selected' : '' }}>
                            {{ $l->nama_layanan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="berat_kg">Berat KG</label>
                <input id="berat_kg" type="number" step="0.01" name="berat_kg" value="{{ old('berat_kg', $transaksi->berat_kg) }}">
            </div>

            <div class="field">
                <label for="total_harga">Total Harga</label>
                <input id="total_harga" type="number" name="total_harga" value="{{ old('total_harga', $transaksi->total_harga) }}">
            </div>

            <div class="field">
                <label for="status_laundry_id">Status Laundry</label>
                <select id="status_laundry_id" name="status_laundry_id">
                    <option value="">Pilih status</option>
                    @foreach($statusLaundry as $status)
                        <option value="{{ $status->id }}" {{ old('status_laundry_id', $transaksi->status_laundry_id) == $status->id ? 'selected' : '' }}>
                            {{ $status->nama_status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="tanggal_masuk">Tanggal Masuk</label>
                <input id="tanggal_masuk" type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', $transaksi->tanggal_masuk) }}">
            </div>

            <div class="field">
                <label for="tanggal_selesai">Tanggal Selesai</label>
                <input id="tanggal_selesai" type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $transaksi->tanggal_selesai) }}">
            </div>
        </div>

        @if(! $transaksi->pembayaran || $transaksi->pembayaran->status_pembayaran !== 'lunas')
            <h3 class="section-title">Data Pembayaran</h3>

            <div class="form-grid">
                <div class="field">
                    <label for="jumlah_bayar">Jumlah Bayar</label>
                    <input id="jumlah_bayar" type="number" name="jumlah_bayar" value="{{ old('jumlah_bayar', $transaksi->pembayaran->jumlah_bayar ?? '') }}" min="0">
                </div>

                <div class="field">
                    <label for="metode_pembayaran">Metode Pembayaran</label>
                    <select id="metode_pembayaran" name="metode_pembayaran">
                        <option value="">Pilih metode</option>
                        <option value="tunai" {{ old('metode_pembayaran', $transaksi->pembayaran->metode_pembayaran ?? '') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="qris" {{ old('metode_pembayaran', $transaksi->pembayaran->metode_pembayaran ?? '') == 'qris' ? 'selected' : '' }}>QRIS</option>
                    </select>
                </div>
            </div>

            @if($transaksi->pembayaran)
                <div class="alert alert-info">
                    Status saat ini: <strong>{{ $transaksi->pembayaran->status_pembayaran }}</strong><br>
                    Sisa: <strong>Rp{{ number_format(max(0, $transaksi->total_harga - $transaksi->pembayaran->jumlah_bayar), 0, ',', '.') }}</strong>
                </div>
            @endif
        @endif

        <div class="actions">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="/transaksi" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
@endsection
