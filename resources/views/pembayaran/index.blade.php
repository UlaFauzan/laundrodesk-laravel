@extends('layout')

@section('title', 'Data Pembayaran')

@section('content')
    <div class="page-header">
        <div>
            <h1>Data Pembayaran</h1>
            <p>Pantau pembayaran tunai, QRIS, dan sisa hutang pelanggan.</p>
        </div>
        <a href="/pembayaran/create" class="btn btn-success">Tambah Pembayaran</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Transaksi</th>
                <th>Jumlah Bayar</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pembayaran as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->transaksi_id }}</td>
                    <td>Rp{{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                    <td>{{ $p->metode_pembayaran }}</td>
                    <td>{{ $p->status_pembayaran }}</td>
                    <td>
                        <div class="actions">
                            <a href="/pembayaran/{{ $p->id }}/edit" class="btn btn-primary">Edit</a>
                            <form class="inline-form" action="/pembayaran/{{ $p->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus pembayaran ini?')">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="table-empty">Belum ada pembayaran</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
