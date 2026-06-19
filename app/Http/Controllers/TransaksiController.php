<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\DetailTransaksi;
use App\Models\LogAktivitas;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Role;
use App\Models\StatusLaundry;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::with(['pelanggan', 'layanan', 'statusLaundry', 'pembayaran'])->get();
        return view('transaksi.index', compact('transaksi'));
    }

    public function create()
    {
        $pelanggan = Pelanggan::all();
        $layanan = Layanan::all();
        $statusLaundry = StatusLaundry::all();
        return view('transaksi.create', compact('pelanggan', 'layanan', 'statusLaundry'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelanggan_id' => 'nullable|exists:pelanggan,id',
            'customer_name' => 'required_without:pelanggan_id|string|max:255',
            'customer_telepon' => 'required_without:pelanggan_id|string|max:20',
            'customer_alamat' => 'required_without:pelanggan_id|string',
            'customer_email' => 'required_without:pelanggan_id|email|max:255',
            'layanan_id' => 'required|exists:layanan,id',
            'berat_kg' => 'required|numeric|min:0.1',
            'total_harga' => 'required|integer|min:0',
            'status_laundry_id' => 'nullable|exists:status_laundry,id',
            'tanggal_masuk' => 'required|date',
            'tanggal_selesai' => 'nullable|date',
        ]);

        $pelangganId = $validated['pelanggan_id'] ?? null;
        $generatedPassword = null;
        $customerEmail = $validated['customer_email'] ?? null;

        if (! $pelangganId) {
            $user = User::where('email', $customerEmail)->first();

            if ($user && $user->role->nama_role !== 'pelanggan') {
                return back()->withErrors(['customer_email' => 'Email sudah digunakan oleh akun non-pelanggan.'])->withInput();
            }

            if ($user && $user->pelanggan) {
                $pelangganId = $user->pelanggan->id;
            } else {
                $pelanggan = Pelanggan::create([
                    'nama' => $validated['customer_name'],
                    'telepon' => $validated['customer_telepon'],
                    'alamat' => $validated['customer_alamat'],
                ]);

                $pelangganId = $pelanggan->id;

                if (! $user) {
                    $pelangganRole = Role::where('nama_role', 'pelanggan')->first();
                    $generatedPassword = $validated['customer_telepon'];

                    User::create([
                        'name' => $validated['customer_name'],
                        'email' => $customerEmail,
                        'password' => Hash::make($generatedPassword),
                        'role_id' => $pelangganRole?->id ?? 4,
                        'pelanggan_id' => $pelangganId,
                    ]);
                } else {
                    $user->pelanggan_id = $pelangganId;
                    $user->save();
                }
            }
        }

        $statusLaundry = null;
        if (! empty($validated['status_laundry_id'])) {
            $statusLaundry = StatusLaundry::find($validated['status_laundry_id']);
        }

        $transaksi = Transaksi::create([
            'pelanggan_id' => $pelangganId,
            'layanan_id' => $validated['layanan_id'],
            'berat_kg' => $validated['berat_kg'],
            'total_harga' => $validated['total_harga'],
            'status' => $statusLaundry?->nama_status ?? 'Menunggu',
            'status_laundry_id' => $statusLaundry?->id ?? null,
            'tanggal_masuk' => $validated['tanggal_masuk'],
        ]);

        DetailTransaksi::create([
            'transaksi_id' => $transaksi->id,
            'layanan_id' => $validated['layanan_id'],
            'berat_kg' => $validated['berat_kg'],
            'subtotal' => $validated['total_harga'],
        ]);

        $transaksi->load('pelanggan', 'statusLaundry');
        $customerName = $transaksi->pelanggan?->nama ?? $validated['customer_name'] ?? 'Pelanggan Baru';
        $statusLabel = $transaksi->statusLaundry?->nama_status ?? $transaksi->status ?? 'pending';

        LogAktivitas::create([
            'nama_pengguna' => $customerName,
            'aktivitas' => 'Transaksi laundry disimpan untuk pelanggan ' . $customerName . ' dengan status "' . $statusLabel . '".',
            'waktu' => now(),
        ]);

        $message = 'Transaksi berhasil dibuat.';
        if ($generatedPassword) {
            $message .= ' Akun pelanggan dibuat dengan email ' . $customerEmail . ' dan password ' . $generatedPassword;
        }

        return redirect()->route('transaksi.index')->with('success', $message);
    }

    public function show(Transaksi $transaksi)
    {
        return view('transaksi.show', compact('transaksi'));
    }

    public function edit(Transaksi $transaksi)
    {
        $transaksi->load('pembayaran');
        $pelanggan = Pelanggan::all();
        $layanan = Layanan::all();
        $statusLaundry = StatusLaundry::all();
        return view('transaksi.edit', compact('transaksi', 'pelanggan', 'layanan', 'statusLaundry'));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $validated = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'layanan_id' => 'required|exists:layanan,id',
            'berat_kg' => 'required|numeric|min:0.1',
            'total_harga' => 'required|integer|min:0',
            'status' => 'nullable|string|in:diterima,dicuci,dikeringkan,disetrika,selesai,diambil',
            'status_laundry_id' => 'nullable|exists:status_laundry,id',
            'tanggal_masuk' => 'required|date',
            'tanggal_selesai' => 'nullable|date',
            'jumlah_bayar' => 'nullable|integer|min:0|required_with:metode_pembayaran',
            'metode_pembayaran' => 'nullable|string|in:tunai,qris|required_with:jumlah_bayar',
        ]);

        $oldStatus = $transaksi->statusLaundry?->nama_status ?? $transaksi->status ?? 'pending';

        $newStatus = null;
        if (! empty($validated['status_laundry_id'])) {
            $newStatus = StatusLaundry::find($validated['status_laundry_id'])?->nama_status;
        }
        if (! $newStatus) {
            $newStatus = $validated['status'] ?? $transaksi->statusLaundry?->nama_status ?? $transaksi->status ?? 'diterima';
        }

        $transaksi->update([
            'pelanggan_id' => $validated['pelanggan_id'],
            'layanan_id' => $validated['layanan_id'],
            'berat_kg' => $validated['berat_kg'],
            'total_harga' => $validated['total_harga'],
            'status' => $newStatus,
            'status_laundry_id' => $validated['status_laundry_id'] ?? null,
            'tanggal_masuk' => $validated['tanggal_masuk'],
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
        ]);

        DetailTransaksi::updateOrCreate(
            ['transaksi_id' => $transaksi->id],
            [
                'layanan_id' => $validated['layanan_id'],
                'berat_kg' => $validated['berat_kg'],
                'subtotal' => $validated['total_harga'],
            ]
        );

        if ($newStatus && $newStatus !== $oldStatus) {
            LogAktivitas::create([
                'nama_pengguna' => $transaksi->pelanggan?->nama ?? 'Pelanggan',
                'aktivitas' => 'Status laundry untuk pelanggan ' . ($transaksi->pelanggan?->nama ?? 'Pelanggan') . ' diperbarui dari "' . $oldStatus . '" menjadi "' . $newStatus . '".',
                'waktu' => now(),
            ]);
        }

        if (! empty($validated['jumlah_bayar']) && ! empty($validated['metode_pembayaran'])) {
            $existingPembayaran = $transaksi->pembayaran;

            if ($validated['metode_pembayaran'] === 'qris') {
                // If a QRIS payment already exists, redirect to the payment edit page
                // so the cashier can add an additional payment amount.
                if ($existingPembayaran) {
                    return redirect()->route('pembayaran.edit', ['pembayaran' => $existingPembayaran->id])
                        ->with('info', 'Transaksi ini sudah memiliki pembayaran. Silakan tambahkan pembayaran tambahan di sini.');
                }

                $pembayaran = Pembayaran::create([
                    'transaksi_id' => $transaksi->id,
                    'jumlah_bayar' => 0,
                    'metode_pembayaran' => 'qris',
                    'status_pembayaran' => 'pending',
                    'qr_token' => (string) \Illuminate\Support\Str::uuid(),
                ]);

                return redirect()->route('pembayaran.confirm', [
                    'pembayaran' => $pembayaran->id,
                    'amount' => $validated['jumlah_bayar'],
                ]);
            }

            $totalBayar = ($existingPembayaran?->jumlah_bayar ?? 0) + $validated['jumlah_bayar'];
            $statusPembayaran = $totalBayar >= $validated['total_harga'] ? 'lunas' : 'hutang';

            Pembayaran::updateOrCreate(
                ['transaksi_id' => $transaksi->id],
                [
                    'jumlah_bayar' => $totalBayar,
                    'metode_pembayaran' => 'tunai',
                    'status_pembayaran' => $statusPembayaran,
                    'qr_token' => null,
                ]
            );
        }

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diupdate');
    }

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus');
    }
}
