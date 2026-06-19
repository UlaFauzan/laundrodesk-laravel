<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;

class KasirDashboardController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::with(['transaksi.pembayaran', 'transaksi.layanan'])->get();
        return view('kasir.dashboard', compact('pelanggan'));
    }
}
