@extends('layout')

@section('title', 'Data Transaksi')

@section('content')
    <div class="page-header">
        <div>
            <h1>Data Transaksi</h1>
            <p>Kelola transaksi laundry dan status pembayaran pelanggan.</p>
        </div>
        <a href="/transaksi/create" class="btn btn-success">Tambah Transaksi</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Pelanggan</th>
                <th>Layanan</th>
                <th>Berat</th>
                <th>Total Harga</th>
                <th>Pembayaran</th>
                <th>Status</th>
                <th>Tanggal Masuk</th>
                <th>Tanggal Selesai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $t)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $t->pelanggan->nama }}</td>
                    <td>{{ $t->layanan->nama_layanan }}</td>
                    <td>{{ $t->berat_kg }} kg</td>
                    <td>Rp{{ number_format($t->total_harga, 0, ',', '.') }}</td>
                    <td>
                        @if($t->pembayaran)
                            @php
                                $sisa = $t->total_harga - $t->pembayaran->jumlah_bayar;
                            @endphp

                            @if($sisa > 0)
                                <span class="status-text status-text--warning">Kurang Bayar</span><br>
                                Rp{{ number_format($t->pembayaran->jumlah_bayar, 0, ',', '.') }}<br>
                                <small class="status-text--danger">Sisa: Rp{{ number_format($sisa, 0, ',', '.') }}</small><br>
                                <small>{{ $t->pembayaran->metode_pembayaran }} / hutang</small>
                            @else
                                <span class="status-text status-text--success">Sudah Bayar</span><br>
                                Rp{{ number_format($t->pembayaran->jumlah_bayar, 0, ',', '.') }}<br>
                                <small>{{ $t->pembayaran->metode_pembayaran }} / {{ $t->pembayaran->status_pembayaran }}</small>
                            @endif
                        @else
                            <span class="status-text status-text--danger">Belum Bayar</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $statusPembayaran = $t->getStatusPembayaranSebenarnya();
                        @endphp

                        @if($statusPembayaran === 'hutang')
                            <span class="status-text status-text--warning">{{ $statusPembayaran }}</span>
                        @else
                            {{ $t->statusLaundry?->nama_status ?? $t->status }}
                        @endif
                    </td>
                    <td>{{ $t->tanggal_masuk }}</td>
                    <td>{{ $t->tanggal_selesai ?? '-' }}</td>
                    <td>
                        <div class="actions">
                            <a href="/transaksi/{{ $t->id }}/edit" class="btn btn-primary">Edit</a>

                            @if ($t->pembayaran)
                                @php
                                    $sisa = $t->total_harga - $t->pembayaran->jumlah_bayar;
                                @endphp
                                @if ($sisa > 0)
                                    <a href="/pembayaran/{{ $t->pembayaran->id }}/edit" class="btn btn-secondary">Bayar Hutang</a>
                                @endif
                            @else
                                <a href="/transaksi/{{ $t->id }}/edit" class="btn btn-secondary">Bayar</a>
                            @endif

                            <form class="inline-form" action="/transaksi/{{ $t->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus transaksi ini?')">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="table-empty">Belum ada transaksi</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
