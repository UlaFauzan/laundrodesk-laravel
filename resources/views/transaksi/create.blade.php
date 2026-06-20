@extends('layout')

@section('title', 'Tambah Transaksi')

@section('content')
    <div class="page-header">
        <div>
            <h1>Tambah Transaksi</h1>
            <p>Masukkan data pelanggan dan detail layanan laundry.</p>
        </div>
    </div>

    <form class="form-card" action="/transaksi" method="POST">
        @csrf

        <h3 class="section-title">Pelanggan</h3>

        <div class="field">
            <label for="pelanggan_id">Pilih Pelanggan Lama</label>
            <select id="pelanggan_id" name="pelanggan_id">
                <option value="">Pelanggan baru</option>
                @foreach($pelanggan as $p)
                    <option value="{{ $p->id }}" {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->nama }} - {{ $p->telepon }}
                    </option>
                @endforeach
            </select>
            <div class="help-text">Jika pelanggan baru, kosongkan pilihan ini dan isi data di bawah.</div>
        </div>

        <div class="form-grid">
            <div class="field">
                <label for="customer_name">Nama Pelanggan</label>
                <input id="customer_name" type="text" name="customer_name" value="{{ old('customer_name') }}">
            </div>

            <div class="field">
                <label for="customer_email">Email Pelanggan</label>
                <input id="customer_email" type="email" name="customer_email" value="{{ old('customer_email') }}">
            </div>

            <div class="field">
                <label for="customer_telepon">Telepon Pelanggan</label>
                <input id="customer_telepon" type="text" name="customer_telepon" value="{{ old('customer_telepon') }}">
            </div>

            <div class="field">
                <label for="customer_alamat">Alamat Pelanggan</label>
                <input id="customer_alamat" type="text" name="customer_alamat" value="{{ old('customer_alamat') }}">
            </div>
        </div>

        <h3 class="section-title">Transaksi</h3>

        <div class="form-grid">
            <div class="field field--full">
                <label for="layanan_id">Layanan</label>
                <select id="layanan_id" name="layanan_id">
                    <option value="">Pilih layanan</option>
                    @foreach($layanan as $l)
                        <option value="{{ $l->id }}" {{ old('layanan_id') == $l->id ? 'selected' : '' }}>
                            {{ $l->nama_layanan }} - Rp{{ number_format($l->harga_per_kg, 0, ',', '.') }}/kg
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="berat_kg">Berat (KG)</label>
                <input id="berat_kg" type="number" step="0.01" min="0.1" name="berat_kg" value="{{ old('berat_kg') }}">
            </div>

            <div class="field">
                <label for="total_harga">Total Harga</label>
                <input id="total_harga" type="number" min="0" name="total_harga" value="{{ old('total_harga') }}">
            </div>

            <div class="field">
                <label for="status_laundry_id">Status Laundry</label>
                <select id="status_laundry_id" name="status_laundry_id">
                    <option value="">Pilih status</option>
                    @foreach($statusLaundry as $status)
                        <option value="{{ $status->id }}" {{ old('status_laundry_id') == $status->id ? 'selected' : '' }}>
                            {{ $status->nama_status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="tanggal_masuk">Tanggal Masuk</label>
                <input id="tanggal_masuk" type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', now()->toDateString()) }}">
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="/transaksi" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
@endsection
