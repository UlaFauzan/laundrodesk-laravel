@extends('layout')

@section('title', 'Tambah Detail Transaksi')

@section('content')
    <div class="page-header">
        <div>
            <h1>Tambah Detail Transaksi</h1>
            <p>Tambahkan rincian layanan untuk transaksi tertentu.</p>
        </div>
    </div>

    <form class="form-card" action="/detail-transaksi" method="POST">
        @csrf

        <div class="form-grid">
            <div class="field">
                <label for="transaksi_id">Transaksi</label>
                <select id="transaksi_id" name="transaksi_id">
                    <option value="">Pilih transaksi</option>
                    @foreach($transaksi as $t)
                        <option value="{{ $t->id }}" {{ old('transaksi_id') == $t->id ? 'selected' : '' }}>
                            #{{ $t->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="layanan_id">Layanan</label>
                <select id="layanan_id" name="layanan_id">
                    <option value="">Pilih layanan</option>
                    @foreach($layanan as $l)
                        <option value="{{ $l->id }}" {{ old('layanan_id') == $l->id ? 'selected' : '' }}>
                            {{ $l->nama_layanan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="berat_kg">Berat (Kg)</label>
                <input id="berat_kg" type="number" step="0.01" name="berat_kg" value="{{ old('berat_kg') }}">
            </div>

            <div class="field">
                <label for="subtotal">Subtotal</label>
                <input id="subtotal" type="number" name="subtotal" value="{{ old('subtotal') }}">
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="/detail-transaksi" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
@endsection
