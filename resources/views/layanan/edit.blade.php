@extends('layout')

@section('title', 'Edit Layanan')

@section('content')
    <div class="page-header">
        <div>
            <h1>Edit Layanan</h1>
            <p>Perbarui data layanan laundry.</p>
        </div>
    </div>

    <form class="form-card" action="/layanan/{{ $layanan->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="field">
                <label for="nama_layanan">Nama Layanan</label>
                <input type="text" id="nama_layanan" name="nama_layanan" value="{{ $layanan->nama_layanan }}" required placeholder="Contoh: Cuci Kering">
            </div>

            <div class="field">
                <label for="harga_per_kg">Harga per Kg</label>
                <input type="number" id="harga_per_kg" name="harga_per_kg" value="{{ $layanan->harga_per_kg }}" required min="0">
            </div>

            <div class="field field--full">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" required placeholder="Deskripsi layanan...">{{ $layanan->deskripsi }}</textarea>
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="/layanan" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
@endsection
