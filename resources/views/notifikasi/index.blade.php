@extends('layout')

@section('title', 'Data Notifikasi')

@section('content')
    <div class="page-header">
        <div>
            <h1>Data Notifikasi</h1>
            <p>Kelola pesan notifikasi yang dikirim ke pelanggan.</p>
        </div>
        <a href="/notifikasi/create" class="btn btn-success">Tambah Notifikasi</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pelanggan</th>
                <th>Pesan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($notifikasi as $n)
                <tr>
                    <td>{{ $n->id }}</td>
                    <td>{{ $n->pelanggan?->nama ?? $n->pelanggan_id }}</td>
                    <td>{{ $n->pesan }}</td>
                    <td>{{ $n->status_baca }}</td>
                    <td>
                        <div class="actions">
                            <a href="/notifikasi/{{ $n->id }}/edit" class="btn btn-primary">Edit</a>
                            <form class="inline-form" action="/notifikasi/{{ $n->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus notifikasi ini?')">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="table-empty">Belum ada notifikasi</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
