@extends('layout')

@section('content')
    <h2>Kelola User</h2>

    <a href="/users/create" class="btn btn-success">+ Tambah User</a>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Pelanggan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role?->nama_role ?? 'Belum ada role' }}</td>
                    <td>{{ $user->pelanggan?->nama ?? '-' }}</td>
                    <td>
                        <a href="/users/{{ $user->id }}" class="btn btn-primary">Lihat</a>
                        <a href="/users/{{ $user->id }}/edit" class="btn btn-primary">Edit</a>
                        <form action="/users/{{ $user->id }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus user ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Belum ada user</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
