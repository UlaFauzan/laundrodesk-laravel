<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Transaksi;
use App\Models\Pelanggan;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayaran = Pembayaran::with('transaksi')->get();
        return view('pembayaran.index', compact('pembayaran'));
    }

    public function create(Request $request)
    {
        $transaksiId = $request->query('transaksi_id');
        $transaksi = $transaksiId ? Transaksi::with('pelanggan', 'layanan')->find($transaksiId) : null;
        $transaksiList = $transaksi
            ? collect([$transaksi])
            : Transaksi::with('pelanggan', 'layanan')->doesntHave('pembayaran')->get();

        return view('pembayaran.create', compact('transaksi', 'transaksiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaksi_id' => 'required|exists:transaksi,id',
            'jumlah_bayar' => 'required|integer|min:0',
            'metode_pembayaran' => 'required|string|in:tunai,qris',
            'status_pembayaran' => 'nullable|string|max:255',
        ]);

        $transaksi = Transaksi::findOrFail($validated['transaksi_id']);

        if ($existing = Pembayaran::where('transaksi_id', $validated['transaksi_id'])->first()) {
            // If a payment already exists, redirect to edit so the cashier can add payment.
            return redirect()->route('pembayaran.edit', ['pembayaran' => $existing->id])
                ->with('info', 'Transaksi ini sudah memiliki pembayaran. Silakan tambahkan pembayaran di sini.');
        }

        $amount = $validated['jumlah_bayar'];

        if ($validated['metode_pembayaran'] === 'qris') {
            // For QRIS, create a pending payment record. The actual amount will be
            // recorded when the customer scans the QR code.
            $validated['qr_token'] = (string) Str::uuid();
            $validated['jumlah_bayar'] = 0;
            $validated['status_pembayaran'] = 'pending';
            $pembayaran = Pembayaran::create($validated);

            return redirect()->route('pembayaran.confirm', ['pembayaran' => $pembayaran->id, 'amount' => $amount])
                ->with('success', 'Pembayaran dibuat — tunjukkan QR ke pelanggan');
        }

        // For cash, record the payment immediately.
        $totalBayar = $amount;
        $statusPembayaran = $totalBayar >= $transaksi->total_harga ? 'lunas' : 'hutang';

        $pembayaran = Pembayaran::create([
            'transaksi_id' => $validated['transaksi_id'],
            'metode_pembayaran' => 'tunai',
            'jumlah_bayar' => $totalBayar,
            'status_pembayaran' => $statusPembayaran,
            'qr_token' => null,
        ]);

        if ($statusPembayaran === 'lunas' && $transaksi) {
            $transaksi->status = 'lunas';
            $transaksi->save();
        }

        return redirect()->route('transaksi.index')->with('success', 'Pembayaran tunai berhasil disimpan.');
    }

    public function show(Pembayaran $pembayaran)
    {
        return view('pembayaran.show', compact('pembayaran'));
    }

    /**
     * Show confirmation page with QR code for cashier after creating a pembayaran.
     */
    public function confirm(Pembayaran $pembayaran)
    {
        $pembayaran->qr_token = (string) Str::uuid();
        $pembayaran->save();

        $amount = request('amount', $pembayaran->jumlah_bayar);
        return view('pembayaran.confirm', compact('pembayaran', 'amount'));
    }

    /**
     * Return pembayaran status as JSON for polling.
     */
    public function status(Pembayaran $pembayaran)
    {
        return response()->json(['status' => $pembayaran->status_pembayaran]);
    }

    /**
     * Return unnotified completed pembayaran for kasir dashboard polling.
     */
    public function notifications()
    {
        $items = Pembayaran::with('transaksi.pelanggan')
            ->whereIn('status_pembayaran', ['lunas', 'hutang'])
            ->where(function($q) { $q->whereNull('notified')->orWhere('notified', false); })
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json(['items' => $items]);
    }

    /**
     * Public endpoint that simulates customer completing a payment by visiting the QR link.
     * Marks pembayaran as 'lunas' and returns a small thank-you page.
     */
    public function complete(Pembayaran $pembayaran, $token)
    {
        // verify token
        if (! $pembayaran->qr_token || $pembayaran->qr_token !== $token) {
            return response('Invalid or expired payment link', 400);
        }

        $amount = (int) request('amount', 0);
        if ($amount <= 0) {
            return response('Invalid payment amount', 400);
        }

        $oldJumlah = $pembayaran->jumlah_bayar;
        $pembayaran->jumlah_bayar = $oldJumlah + $amount;
        $transaksi = $pembayaran->transaksi;
        $totalHarga = $transaksi?->total_harga ?? 0;
        $pembayaran->status_pembayaran = $pembayaran->jumlah_bayar >= $totalHarga ? 'lunas' : 'hutang';
        $pembayaran->qr_token = null;
        $pembayaran->notified = false;
        $pembayaran->save();

        // update transaksi status when fully paid
        if ($transaksi && $pembayaran->status_pembayaran === 'lunas') {
            $transaksi->status = 'lunas';
            $transaksi->save();
        }

        return view('pembayaran.complete', compact('pembayaran'));
    }

    /**
     * Mark a pembayaran as notified so dashboards don't notify repeatedly.
     */
    public function notify(Pembayaran $pembayaran)
    {
        $pembayaran->notified = true;
        $pembayaran->save();

        return response()->json(['ok' => true]);
    }

    public function edit(Pembayaran $pembayaran)
    {
        $pembayaran->load('transaksi.pelanggan', 'transaksi.layanan');

        $totalHarga = $pembayaran->transaksi->total_harga;
        $sudahBayar = $pembayaran->jumlah_bayar;
        $sisa = max(0, $totalHarga - $sudahBayar);

        if ($sisa <= 0) {
            return redirect()->route('pembayaran.index')->with('success', 'Pembayaran sudah lunas dan tidak perlu diupdate.');
        }

        return view('pembayaran.edit', compact('pembayaran', 'sisa'));
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        $validated = $request->validate([
            'transaksi_id' => 'required|exists:transaksi,id',
            'jumlah_bayar' => 'required|integer|min:1',
            'metode_pembayaran' => 'required|string|in:tunai,qris',
        ]);

        $transaksi = $pembayaran->transaksi;
        $totalHarga = $transaksi->total_harga;
        $sudahBayar = $pembayaran->jumlah_bayar;
        $sisa = max(0, $totalHarga - $sudahBayar);

        if ($validated['jumlah_bayar'] > $sisa) {
            return back()->withErrors(['jumlah_bayar' => 'Jumlah bayar tidak boleh lebih dari sisa hutang.'])->withInput();
        }

        // `jumlah_bayar` di form ini diperlakukan sebagai pembayaran tambahan.
        $totalBayar = $sudahBayar + $validated['jumlah_bayar'];
        $statusPembayaran = $totalBayar >= $totalHarga ? 'lunas' : 'hutang';

        $pembayaran->update([
            'transaksi_id' => $validated['transaksi_id'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'jumlah_bayar' => $totalBayar,
            'status_pembayaran' => $statusPembayaran,
            'qr_token' => (string) Str::uuid(),
        ]);

        // update transaksi jika sudah lunas
        if ($pembayaran->status_pembayaran === 'lunas' && $transaksi) {
            $transaksi->status = 'lunas';
            $transaksi->save();
        }

        return redirect()->route('pembayaran.confirm', ['pembayaran' => $pembayaran->id, 'amount' => $validated['jumlah_bayar']])->with('success', 'Pembayaran diperbarui — tunjukkan QR ke pelanggan');
    }

    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();
        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil dihapus');
    }
}