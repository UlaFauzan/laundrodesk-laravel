@extends('layout')

@section('content')
    <h2>Edit Profil Pelanggan</h2>

    @if ($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 20px; padding: 15px; background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pelanggan.profile.update') }}" method="POST" style="max-width: 600px;">
        @csrf
        @method('PUT')

        <div class="form-group" style="margin-bottom: 15px;">
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" value="{{ old('nama', $pelanggan->nama) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label for="telepon">Telepon</label>
            <input type="text" id="telepon" name="telepon" value="{{ old('telepon', $pelanggan->telepon) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label for="alamat">Alamat</label>
            <input type="text" id="alamat" name="alamat" value="{{ old('alamat', $pelanggan->alamat) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('pelanggan.profile') }}" class="btn btn-secondary" style="margin-left: 10px;">Batal</a>
    </form>
@endsection
