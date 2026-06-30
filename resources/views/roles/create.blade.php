@extends('layout')

@section('title', 'Tambah Role')

@section('content')
    <div class="page-header">
        <div>
            <h1>Tambah Role</h1>
            <p>Buat role baru untuk akses pengguna.</p>
        </div>
    </div>

    <form class="form-card" action="/roles" method="POST">
        @csrf

        <div class="form-grid">
            <div class="field field--full">
                <label for="nama_role">Nama Role</label>
                <input id="nama_role" type="text" name="nama_role" placeholder="Contoh: kasir" required>
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="/roles" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
@endsection
