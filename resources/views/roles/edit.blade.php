@extends('layout')

@section('title', 'Edit Role')

@section('content')
    <div class="page-header">
        <div>
            <h1>Edit Role</h1>
            <p>Perbarui nama role yang sudah ada.</p>
        </div>
    </div>

    <form class="form-card" action="/roles/{{ $role->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="field field--full">
                <label for="nama_role">Nama Role</label>
                <input id="nama_role" type="text" name="nama_role" value="{{ old('nama_role', $role->nama_role) }}" placeholder="Contoh: kasir" required>
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="/roles" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
@endsection
