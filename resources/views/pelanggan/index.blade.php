@extends('layout')

@section('content')
    <h2>Daftar Pelanggan</h2>
    
    @if(auth()->user()->role->nama_role == 'admin')
        <a href="/pelanggan/create" class="btn btn-success">+ Tambah Pelanggan</a>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pelanggan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->telepon }}</td>
                    <td>{{ $item->alamat }}</td>
                    <td>
                        <a href="/pelanggan/{{ $item->id }}" class="btn btn-primary">Lihat</a>
                        @if(auth()->user()->role->nama_role == 'admin')
                            <a href="/pelanggan/{{ $item->id }}/edit" class="btn btn-primary">Edit</a>
                            <form method="POST" action="/pelanggan/{{ $item->id }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin?')">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
