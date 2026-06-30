@extends('layout')

@section('title', 'Data Role')

@section('content')
    <div class="page-header">
        <div>
            <h1>Data Role</h1>
            <p>Kelola role pengguna sistem.</p>
        </div>
        <a href="/roles/create" class="btn btn-success">+ Tambah Role</a>
    </div>

    <div class="table-wrapper">
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
                            <div class="actions">
                                <a href="/roles/{{ $role->id }}/edit" class="btn btn-primary">Edit</a>
                                @if(auth()->user()->role->nama_role == 'admin')
                                    <form action="/roles/{{ $role->id }}" method="POST" class="inline-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin?')">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="table-empty">Belum ada role</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
