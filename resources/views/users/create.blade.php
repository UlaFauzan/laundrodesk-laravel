@extends('layout')

@section('content')
    <h2>Tambah User</h2>

    <form action="/users" method="POST">
        @csrf

        <label>Nama</label>
        <input type="text" name="name" value="{{ old('name') }}" required>

        <br><br>

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>

        <br><br>

        <label>Role</label>
        <select name="role_id" required>
            <option value="">-- Pilih Role --</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                    {{ $role->nama_role }}
                </option>
            @endforeach
        </select>

        <br><br>

        <label>Password</label>
        <input type="password" name="password" required>

        <br><br>

        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" required>

        <br><br>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="/users" class="btn btn-primary">Kembali</a>
    </form>
@endsection
