@extends('layout')

@section('content')
    <h2>Data Status Laundry</h2>

    <a href="/status-laundry/create" class="btn btn-success">+ Tambah Status</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($status as $s)
                <tr>
                    <td>{{ $s->id }}</td>
                    <td>{{ $s->nama_status }}</td>
                    <td>
                        <a href="/status-laundry/{{ $s->id }}/edit" class="btn btn-primary">Edit</a>
                        @if(auth()->user()->role->nama_role == 'admin')
                            <form action="/status-laundry/{{ $s->id }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin?')">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Belum ada status</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
