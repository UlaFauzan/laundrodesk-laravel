@extends('layout')

@section('content')
    <h2>Detail User</h2>

    <table class="table">
        <tr>
            <th>Nama</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th>Role</th>
            <td>{{ $user->role?->nama_role ?? 'Belum ada role' }}</td>
        </tr>
        <tr>
            <th>Pelanggan</th>
            <td>{{ $user->pelanggan?->nama ?? '-' }}</td>
        </tr>
    </table>

    <a href="/users/{{ $user->id }}/edit" class="btn btn-primary">Edit</a>
    <a href="/users" class="btn btn-primary">Kembali</a>
@endsection
