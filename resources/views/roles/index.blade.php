@extends('layout')

@section('content')
    <h2>Data Role</h2>

    <a href="/roles/create" class="btn btn-success">+ Tambah Role</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->nama_role }}</td>
                    <td>
                        <a href="/roles/{{ $role->id }}/edit" class="btn btn-primary">Edit</a>
                        @if(auth()->user()->role->nama_role == 'admin')
                            <form action="/roles/{{ $role->id }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin?')">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Belum ada role</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
