@extends('layout')

@section('title', 'Data Status Laundry')

@section('content')
    <div class="page-header">
        <div>
            <h1>Data Status Laundry</h1>
            <p>Kelola status proses laundry.</p>
        </div>
        <a href="/status-laundry/create" class="btn btn-success">+ Tambah Status</a>
    </div>

    <div class="table-wrapper">
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
                            <div class="actions">
                                <a href="/status-laundry/{{ $s->id }}/edit" class="btn btn-primary">Edit</a>
                                @if(auth()->user()->role->nama_role == 'admin')
                                    <form action="/status-laundry/{{ $s->id }}" method="POST" class="inline-form">
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
                        <td colspan="3" class="table-empty">Belum ada status</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
