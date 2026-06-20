<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use App\Models\Layanan;

class DetailTransaksiController extends Controller
{
    public function index()
    {
        $detail = DetailTransaksi::with(['transaksi', 'layanan'])->get();
        return view('detail_transaksi.index', compact('detail'));
    }

    public function create()
    {
        $transaksi = Transaksi::all();
        $layanan = Layanan::all();
        return view('detail_transaksi.create', compact('transaksi', 'layanan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaksi_id' => 'required|exists:transaksi,id',
            'layanan_id' => 'required|exists:layanan,id',
            'berat_kg' => 'required|numeric|min:0.1',
            'subtotal' => 'required|integer|min:0',
        ]);

        DetailTransaksi::create($validated);
        return redirect()->route('detail-transaksi.index')->with('success', 'Detail transaksi berhasil ditambahkan');
    }

    public function show(DetailTransaksi $detailTransaksi)
    {
        return view('detail_transaksi.show', compact('detailTransaksi'));
    }

    public function edit(DetailTransaksi $detailTransaksi)
    {
        $transaksi = Transaksi::all();
        $layanan = Layanan::all();
        return view('detail_transaksi.edit', compact('detailTransaksi', 'transaksi', 'layanan'));
    }

    public function update(Request $request, DetailTransaksi $detailTransaksi)
    {
        $validated = $request->validate([
            'transaksi_id' => 'required|exists:transaksi,id',
            'layanan_id' => 'required|exists:layanan,id',
            'berat_kg' => 'required|numeric|min:0.1',
            'subtotal' => 'required|integer|min:0',
        ]);

        $detailTransaksi->update($validated);
        return redirect()->route('detail-transaksi.index')->with('success', 'Detail transaksi berhasil diupdate');
    }

    public function destroy(DetailTransaksi $detailTransaksi)
    {
        $detailTransaksi->delete();
        return redirect()->route('detail-transaksi.index')->with('success', 'Detail transaksi berhasil dihapus');
    }
}
