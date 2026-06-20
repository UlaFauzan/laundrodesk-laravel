@extends('layout')

@section('title', 'Tambah Pembayaran')

@section('content')
    <div class="page-header">
        <div>
            <h1>Tambah Pembayaran</h1>
            <p>Catat pembayaran baru untuk transaksi yang belum memiliki pembayaran.</p>
        </div>
    </div>

    <form class="form-card" action="/pembayaran" method="POST">
        @csrf

        <div class="field">
            <label for="transaksi_id">ID Transaksi</label>
            @if(isset($transaksi) && $transaksi)
                <div
                    id="selected-transaction"
                    class="alert alert-info"
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
        </div>

        <div class="form-grid">
            <div class="field">
                <label for="jumlah_bayar">Jumlah Bayar</label>
                <input id="jumlah_bayar" type="number" name="jumlah_bayar" value="{{ old('jumlah_bayar') }}">
            </div>

            <div class="field">
                <label for="metode_pembayaran">Metode Pembayaran</label>
                <select id="metode_pembayaran" name="metode_pembayaran">
                    <option value="">Pilih metode</option>
                    <option value="tunai" {{ old('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                    <option value="qris" {{ old('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>

            <div class="field field--full">
                <label for="status_pembayaran">Status Pembayaran</label>
                <input id="status_pembayaran" type="text" name="status_pembayaran" value="{{ old('status_pembayaran', 'pending') }}">
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="/pembayaran" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
@endsection
