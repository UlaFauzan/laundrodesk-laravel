<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\LogAktivitas;
use Carbon\Carbon;

class LaporanPendapatanController extends Controller
{
    /**
     * Display a listing of the laporan pendapatan.
     */
    public function index()
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $pendapatanHari = Transaksi::whereDate('tanggal_masuk', $today->toDateString())->sum('total_harga');
        $pendapatanMinggu = Transaksi::whereBetween('tanggal_masuk', [
            $startOfWeek->toDateString(),
            $endOfWeek->toDateString(),
        ])->sum('total_harga');
        $pendapatanBulan = Transaksi::whereBetween('tanggal_masuk', [
            $startOfMonth->toDateString(),
            $endOfMonth->toDateString(),
        ])->sum('total_harga');

        $pengeluaran = 0;

        $chartData = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);
            $total = Transaksi::whereDate('tanggal_masuk', $date->toDateString())->sum('total_harga');

            return [
                'label' => $date->format('d/m'),
                'total' => $total,
            ];
        });

        $maxChartValue = max($chartData->max('total'), 1);
        $chartData = $chartData->map(function ($item) use ($maxChartValue) {
            $item['height'] = max(($item['total'] / $maxChartValue) * 100, $item['total'] > 0 ? 8 : 0);
            return $item;
        });

        $logAktivitas = LogAktivitas::orderBy('waktu', 'desc')->limit(10)->get();

        return view('laporan-pendapatan.index', compact(
            'pendapatanHari',
            'pendapatanMinggu',
            'pendapatanBulan',
            'pengeluaran',
            'chartData',
            'logAktivitas'
        ));
    }
}
