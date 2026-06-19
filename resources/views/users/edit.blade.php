@extends('layout')

@section('content')
    <h2>Edit User</h2>

    <form action="/users/{{ $user->id }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nama</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>

        <br><br>

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>

        <br><br>

        <label>Role</label>
        <select name="role_id" required>
            <option value="">-- Pilih Role --</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                    {{ $role->nama_role }}
                </option>
            @endforeach
        </select>

        <br><br>

        <label>Password Baru</label>
        <input type="password" name="password">

        <br><br>

        <label>Konfirmasi Password Baru</label>
        <input type="password" name="password_confirmation">

        <br><br>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="/users" class="btn btn-primary">Kembali</a>
    </form>
@endsection
