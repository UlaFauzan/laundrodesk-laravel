@extends('layout')

@section('title', 'Poin Loyalty Saya')

@section('content')
    <h2>Poin Loyalty Saya</h2>

    <div class="page-card" style="margin-bottom: 18px; padding: 18px; border-radius: 12px; background: #f8fafc; border: 1px solid #e2e8f0;">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:14px; flex-wrap:wrap;">
            <div>
                <div style="font-weight: 900; font-size: 18px; margin-bottom: 6px;">Total Poin</div>
                <div style="font-size: 32px; color: #1d4ed8;">{{ $pelanggan->poin ?? 0 }}</div>
            </div>
            <div style="text-align:right; color: #475569; font-size: 14px; max-width: 280px; line-height: 1.5;">
                Poin akan ditambahkan otomatis ketika pesanan laundry Anda selesai.
            </div>
        </div>
    </div>

    <div style="margin-bottom: 18px; display:flex; gap:10px; flex-wrap:wrap;">
        <a href="{{ route('pelanggan.profile') }}" class="btn btn-secondary">Kembali ke Profil</a>
        <a href="{{ route('pelanggan.notifications') }}" class="btn btn-primary">Notifikasi</a>
        <a href="{{ route('pelanggan.status') }}" class="btn btn-success">Cek Status Laundry</a>
    </div>

    @if ($points->isEmpty())
        <p>Belum ada riwayat poin.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Jumlah Poin</th>
                    <th>Alasan</th>
                    <th>Transaksi</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($points as $point)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $point->jumlah_poin }}</td>
                        <td>{{ $point->alasan }}</td>
                        <td>{{ $point->transaksi_id ? '#' . $point->transaksi_id : '-' }}</td>
                        <td>{{ $point->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
