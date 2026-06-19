@extends('layout')

@section('content')
    <h2>Data Layanan</h2>

    <a href="/layanan/create" class="btn btn-success">+ Tambah Layanan</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Layanan</th>
                <th>Harga per Kg</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($layanan as $l)
                <tr>
                    <td>{{ $l->id }}</td>
                    <td>{{ $l->nama_layanan }}</td>
                    <td>Rp {{ number_format($l->harga_per_kg, 0, ',', '.') }}</td>
                    <td>{{ $l->deskripsi }}</td>
                    <td>
                        <a href="/layanan/{{ $l->id }}/edit" class="btn btn-primary">Edit</a>
                        @if(auth()->user()->role->nama_role == 'admin')
                            <form action="/layanan/{{ $l->id }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin?')">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada layanan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
