@extends('layout')

@section('content')
    <h2>Detail Laporan Pendapatan</h2>

    <table class="table">
        <tr>
            <th>Pelanggan</th>
            <td>{{ $laporan->pelanggan?->nama ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Layanan</th>
            <td>{{ $laporan->layanan?->nama_layanan ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Berat</th>
            <td>{{ $laporan->berat_kg }} kg</td>
        </tr>
        <tr>
            <th>Total Harga</th>
            <td>Rp {{ number_format($laporan->total_harga, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Status Laundry</th>
            <td>{{ $laporan->statusLaundry?->nama_status ?? $laporan->status }}</td>
        </tr>
        <tr>
            <th>Tanggal Masuk</th>
            <td>{{ $laporan->tanggal_masuk }}</td>
        </tr>
        <tr>
            <th>Tanggal Selesai</th>
            <td>{{ $laporan->tanggal_selesai ?? '-' }}</td>
        </tr>
    </table>

    <a href="/laporan-pendapatan" class="btn btn-primary">Kembali</a>
@endsection
