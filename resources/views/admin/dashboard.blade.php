@extends('layout')

@section('content')
    <style>
        .admin-dashboard {
            display: grid;
            gap: 20px;
        }

        .admin-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
        }

        .admin-card {
            background: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 6px;
            padding: 16px;
        }

        .admin-card span {
            color: #666666;
            display: block;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .admin-card strong {
            color: #222222;
            display: block;
            font-size: 26px;
        }

        .admin-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 12px;
        }

        .admin-action {
            background: #f7f7f7;
            border: 1px solid #dddddd;
            border-radius: 6px;
            color: #222222;
            display: block;
            padding: 14px;
            text-decoration: none;
        }

        .admin-action:hover {
            background: #eeeeee;
        }

        .admin-charts {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .chart-container {
            background: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 6px;
            padding: 18px;
        }

        .chart-container h3 {
            margin-bottom: 15px;
            color: #222222;
            font-size: 15px;
        }

        .activity-container {
            background: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 6px;
            padding: 18px;
            margin-top: 20px;
        }

        .activity-container h3 {
            margin-bottom: 15px;
            color: #222222;
            font-size: 15px;
        }

        .activity-list {
            list-style: none;
        }

        .activity-item {
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-time {
            color: #7c3aed;
            font-weight: 800;
            font-size: 13px;
            min-width: 60px;
        }

        .activity-text {
            color: #666666;
            font-size: 13px;
            flex: 1;
            margin-left: 12px;
        }
    </style>

    <div class="admin-dashboard">
        <h2>Dashboard Admin</h2>

        <div class="admin-summary">
            <div class="admin-card">
                <span>Total Transaksi</span>
                <strong>{{ $totalTransaksi ?? 0 }}</strong>
            </div>

            <div class="admin-card">
                <span>Transaksi Hari Ini</span>
                <strong>{{ $transaksiHariIni ?? 0 }}</strong>
            </div>

            <div class="admin-card">
                <span>Pendapatan Bulan Ini</span>
                <strong>Rp{{ number_format($pendapatanBulanIni ?? 0,0,',','.') }}</strong>
            </div>

            <div class="admin-card">
                <span>Laundry Diproses</span>
                <strong>{{ $laundryDiproses ?? 0 }}</strong>
            </div>

            <div class="admin-card">
                <span>Laundry Selesai</span>
                <strong>{{ $laundrySelesai ?? 0 }}</strong>
            </div>

            <div class="admin-card">
                <span>Belum Dibayar</span>
                <strong>{{ $belumDibayar ?? 0 }}</strong>
            </div>

            <div class="admin-card">
                <span>Role</span>
                <strong>{{ $totalRoles }}</strong>
            </div>

            <div class="admin-card">
                <span>User</span>
                <strong>{{ $totalUsers }}</strong>
            </div>

            <div class="admin-card">
                <span>Layanan</span>
                <strong>{{ $totalLayanan }}</strong>
            </div>

            <div class="admin-card">
                <span>Status Laundry</span>
                <strong>{{ $totalStatusLaundry }}</strong>
            </div>

            <div class="admin-card">
                <span>Pelanggan</span>
                <strong>{{ $totalPelanggan }}</strong>
            </div>

            <div class="admin-card">
                <span>Log Aktivitas</span>
                <strong>{{ $totalLogAktivitas }}</strong>
            </div>
        </div>

        {{-- Admin action buttons removed as requested --}}

        <div class="admin-charts">
            <div class="chart-container">
                <h3>Grafik Pendapatan Bulanan</h3>
                <canvas id="revenueChart"></canvas>
            </div>

            <div class="chart-container">
                <h3>Grafik Jumlah Transaksi</h3>
                <canvas id="transactionChart"></canvas>
            </div>

            <div class="chart-container">
                <h3>Pie Chart Status Laundry</h3>
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <div class="activity-container">
            <h3>Aktivitas Terbaru</h3>
            @if($recentActivities->isEmpty())
                <p style="color: #999999; font-size: 13px;">Belum ada aktivitas tercatat.</p>
            @else
                <ul class="activity-list">
                    @foreach($recentActivities as $activity)
                        <li class="activity-item">
                            <span class="activity-time">{{ $activity->waktu->format('H:i') }}</span>
                            <span class="activity-text">
                                <strong>{{ $activity->nama_pengguna }}</strong> {{ $activity->aktivitas }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Monthly Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json(array_keys($monthlyRevenue ?? [])),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: @json(array_values($monthlyRevenue ?? [])),
                    borderColor: '#7c3aed',
                    backgroundColor: 'rgba(124, 58, 237, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: true } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Transaction by Status Chart
        const transactionCtx = document.getElementById('transactionChart').getContext('2d');
        new Chart(transactionCtx, {
            type: 'bar',
            data: {
                labels: @json(array_keys($transaksiByStatus ?? [])),
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: @json(array_values($transaksiByStatus ?? [])),
                    backgroundColor: [
                        '#7c3aed',
                        '#a78bfa',
                        '#ddd6fe',
                        '#ede9fe',
                        '#f3e8ff'
                    ],
                    borderColor: '#7c3aed',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: true } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Status Laundry Pie Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: @json(array_keys($transaksiByStatus ?? [])),
                datasets: [{
                    data: @json(array_values($transaksiByStatus ?? [])),
                    backgroundColor: [
                        '#7c3aed',
                        '#a78bfa',
                        '#ddd6fe',
                        '#ede9fe',
                        '#f3e8ff'
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: true } }
            }
        });
    </script>
@endsection
