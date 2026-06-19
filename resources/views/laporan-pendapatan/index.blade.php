@extends('layout')

@section('content')
    <style>
        .manager-dashboard {
            display: grid;
            gap: 20px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 14px;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 6px;
            padding: 16px;
        }

        .stat-card span {
            color: #666666;
            display: block;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .stat-card strong {
            color: #222222;
            display: block;
            font-size: 24px;
        }

        .chart-section {
            background: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 6px;
            padding: 18px;
        }

        .chart-title {
            font-size: 18px;
            margin-bottom: 18px;
        }

        .bar-chart {
            align-items: end;
            border-bottom: 1px solid #dddddd;
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(7, minmax(36px, 1fr));
            height: 260px;
            padding-top: 12px;
        }

        .bar-item {
            align-items: center;
            display: flex;
            flex-direction: column;
            gap: 8px;
            height: 100%;
            justify-content: end;
            min-width: 0;
        }

        .bar-value {
            color: #333333;
            font-size: 12px;
            min-height: 16px;
            text-align: center;
            width: 100%;
        }

        .bar {
            background: #007bff;
            border-radius: 4px 4px 0 0;
            min-height: 0;
            width: 100%;
        }

        .bar-label {
            color: #555555;
            font-size: 12px;
            padding-bottom: 8px;
        }
    </style>

    <div class="manager-dashboard">
        <h2>Dashboard Manager</h2>

        <div class="summary-grid">
            <div class="stat-card">
                <span>Pendapatan Hari Ini</span>
                <strong>Rp {{ number_format($pendapatanHari, 0, ',', '.') }}</strong>
            </div>

            <div class="stat-card">
                <span>Pendapatan Minggu Ini</span>
                <strong>Rp {{ number_format($pendapatanMinggu, 0, ',', '.') }}</strong>
            </div>

            <div class="stat-card">
                <span>Pendapatan Bulan Ini</span>
                <strong>Rp {{ number_format($pendapatanBulan, 0, ',', '.') }}</strong>
            </div>

            <div class="stat-card">
                <span>Pengeluaran</span>
                <strong>Rp {{ number_format($pengeluaran, 0, ',', '.') }}</strong>
            </div>
        </div>

        <div class="chart-section">
            <h3 class="chart-title">Statistik Pendapatan 7 Hari Terakhir</h3>

            <div class="bar-chart">
                @foreach($chartData as $item)
                    <div class="bar-item">
                        <div class="bar-value">Rp {{ number_format($item['total'], 0, ',', '.') }}</div>
                        <div class="bar" style="height: {{ $item['height'] }}%;"></div>
                        <div class="bar-label">{{ $item['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Card Log Aktivitas --}}
        <div class="chart-section">
            <h3 class="chart-title">Log Aktivitas Terbaru</h3>

            <table class="table">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Aktivitas</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logAktivitas as $log)
                        <tr>
                            <td>{{ $log->nama_pengguna }}</td>
                            <td>{{ $log->aktivitas }}</td>
                            <td>{{ $log->waktu ? $log->waktu->format('d/m/Y H:i') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: var(--muted);">Belum ada log aktivitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
