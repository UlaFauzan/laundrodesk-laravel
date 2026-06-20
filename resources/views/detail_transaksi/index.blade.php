@extends('layout')

@section('title', 'Data Detail Transaksi')

@section('content')
    <div class="page-header">
        <div>
            <h1>Data Detail Transaksi</h1>
            <p>Rincian layanan, berat, dan subtotal untuk tiap transaksi.</p>
        </div>
        <a href="/detail-transaksi/create" class="btn btn-success">Tambah Detail Transaksi</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Transaksi</th>
                <th>Layanan</th>
                <th>Berat (Kg)</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($detail as $d)
                <tr>
                    <td>{{ $d->id }}</td>
                    <td>#{{ $d->transaksi_id }}</td>
                    <td>{{ $d->layanan?->nama_layanan ?? $d->layanan_id }}</td>
                    <td>{{ $d->berat_kg }}</td>
                    <td>Rp{{ number_format($d->subtotal, 0, ',', '.') }}</td>
                    <td>
                        <div class="actions">
                            <a href="/detail-transaksi/{{ $d->id }}/edit" class="btn btn-primary">Edit</a>
                            <form class="inline-form" action="/detail-transaksi/{{ $d->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus detail transaksi ini?')">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="table-empty">Belum ada detail transaksi</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
