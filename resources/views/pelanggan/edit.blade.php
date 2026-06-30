@extends('layout')

@section('title', 'Edit Pelanggan')

@section('content')
    <div class="page-header">
        <div>
            <h1>Edit Pelanggan</h1>
            <p>Perbarui data pelanggan yang terdaftar.</p>
        </div>
    </div>

    <form class="form-card" action="/pelanggan/{{ $pelanggan->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="field">
                <label for="nama">Nama</label>
                <input id="nama" type="text" name="nama" value="{{ old('nama', $pelanggan->nama) }}" required placeholder="Masukkan nama pelanggan">
            </div>

            <div class="field">
                <label for="telepon">Telepon</label>
                <input id="telepon" type="text" name="telepon" value="{{ old('telepon', $pelanggan->telepon) }}" required placeholder="Contoh: 0812xxxx">
            </div>

            <div class="field field--full">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat" rows="4" required placeholder="Masukkan alamat pelanggan">{{ old('alamat', $pelanggan->alamat) }}</textarea>
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="/pelanggan" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
@endsection