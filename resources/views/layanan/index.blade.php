@extends('layout')

@section('title', 'Data Layanan')

@section('content')
    <div class="page-header">
        <div>
            <h1>Data Layanan</h1>
            <p>Kelola layanan laundry yang tersedia.</p>
        </div>
        <a href="/layanan/create" class="btn btn-success">+ Tambah Layanan</a>
    </div>

    <div class="table-wrapper">
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
                            <a href="/layanan/{{ $l->id }}/edit" class="btn btn-primary btn-compact">Edit</a>
                            @if(auth()->user()->role->nama_role == 'admin')
                                <form action="/layanan/{{ $l->id }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-compact" onclick="return confirm('Yakin?')">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="table-empty">Belum ada layanan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
