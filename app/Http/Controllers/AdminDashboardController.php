<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\LogAktivitas;
use App\Models\Pelanggan;
use App\Models\Role;
use App\Models\StatusLaundry;
use App\Models\User;
use App\Models\Transaksi;
use App\Models\Pembayaran;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalTransaksi = Transaksi::count();
        $transaksiHariIni = Transaksi::whereDate('tanggal_masuk', Carbon::today())->count();
        $pendapatanBulanIni = Pembayaran::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('jumlah_bayar');
        $laundryDiproses = Transaksi::whereNull('tanggal_selesai')->count();
        $laundrySelesai = Transaksi::whereNotNull('tanggal_selesai')->count();
        $belumDibayar = Transaksi::doesntHave('pembayaran')->count();

        // Chart data: monthly revenue (last 12 months)
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenue = Pembayaran::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('jumlah_bayar');
            $monthlyRevenue[$date->format('M Y')] = $revenue;
        }

        // Chart data: transaction count by status
        $transaksiByStatus = [];
        $statuses = StatusLaundry::all();
        foreach ($statuses as $status) {
            $transaksiByStatus[$status->nama_status] = Transaksi::where('status_laundry_id', $status->id)->count();
        }

        // Recent activities
        $recentActivities = LogAktivitas::orderBy('created_at', 'desc')->limit(10)->get();

        return view('admin.dashboard', [
            'totalRoles' => Role::count(),
            'totalUsers' => User::count(),
            'totalLayanan' => Layanan::count(),
            'totalStatusLaundry' => StatusLaundry::count(),
            'totalPelanggan' => Pelanggan::count(),
            'totalLogAktivitas' => LogAktivitas::count(),
            'totalTransaksi' => $totalTransaksi,
            'transaksiHariIni' => $transaksiHariIni,
            'pendapatanBulanIni' => $pendapatanBulanIni,
            'laundryDiproses' => $laundryDiproses,
            'laundrySelesai' => $laundrySelesai,
            'belumDibayar' => $belumDibayar,
            'monthlyRevenue' => $monthlyRevenue,
            'transaksiByStatus' => $transaksiByStatus,
            'recentActivities' => $recentActivities,
        ]);
    }
}
