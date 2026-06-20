<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\Notifikasi;
use App\Models\TambahPoin;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::all();
        return view('pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        Pelanggan::create($validated);
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function show(Pelanggan $pelanggan)
    {
        return view('pelanggan.show', compact('pelanggan'));
    }

    public function profile()
    {
        $pelanggan = auth()->user()->pelanggan;
        $notifications = collect();

        if ($pelanggan) {
            $notifications = $pelanggan->notifikasi()
                ->where('status_baca', '!=', 'Sudah Dibaca')
                ->orderByDesc('created_at')
                ->get();
        }

        return view('pelanggan.profile', [
            'user' => auth()->user(),
            'notifications' => $notifications,
        ]);
    }

    public function notifications()
    {
        $pelanggan = auth()->user()->pelanggan;

        if (! $pelanggan) {
            return redirect()->route('pelanggan.profile')->withErrors('Data pelanggan tidak ditemukan.');
        }

        $notifications = $pelanggan->notifikasi()
            ->orderByDesc('created_at')
            ->get();

        return view('pelanggan.notifications', compact('notifications'));
    }

    public function markNotificationRead(Notifikasi $notifikasi)
    {
        $pelanggan = auth()->user()->pelanggan;
        if (! $pelanggan || $notifikasi->pelanggan_id !== $pelanggan->id) {
            abort(403);
        }

        $notifikasi->update(['status_baca' => 'Sudah Dibaca']);
        return back()->with('success', 'Notifikasi berhasil ditandai sudah dibaca.');
    }

    public function status()
    {
        $pelanggan = auth()->user()->pelanggan;

        if (! $pelanggan) {
            return redirect()->route('pelanggan.profile')->withErrors('Data pelanggan tidak ditemukan.');
        }

        $transaksi = Transaksi::with(['layanan', 'statusLaundry', 'pembayaran'])
            ->where('pelanggan_id', $pelanggan->id)
            ->get();

        return view('pelanggan.status', compact('transaksi'));
    }

    public function transactions()
    {
        try {
            $pelanggan = auth()->user()->pelanggan;

            if (! $pelanggan) {
                return redirect()->route('pelanggan.profile')->withErrors('Data pelanggan tidak ditemukan.');
            }

            $transaksi = Transaksi::with(['layanan', 'statusLaundry', 'pembayaran', 'tambahPoin'])
                ->where('pelanggan_id', $pelanggan->id)
                ->orderBy('tanggal_masuk', 'desc')
                ->paginate(10);

            return view('pelanggan.transactions', compact('transaksi'));
        } catch (\Exception $e) {
            return view('error.error-page', [
                'page_name' => 'Halaman Riwayat Transaksi',
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
            ]);
        }
    }

    public function points()
    {
        $pelanggan = auth()->user()->pelanggan;

        if (! $pelanggan) {
            return redirect()->route('pelanggan.profile')->withErrors('Data pelanggan tidak ditemukan.');
        }

        $points = $pelanggan->tambahPoin()->orderByDesc('created_at')->get();

        return view('pelanggan.points', compact('pelanggan', 'points'));
    }

    public function showTransaction(Transaksi $transaksi)
    {
        try {
            $pelanggan = auth()->user()->pelanggan;
            if (! $pelanggan || $transaksi->pelanggan_id !== $pelanggan->id) {
                abort(403);
            }

            $transaksi->load(['layanan', 'statusLaundry', 'pembayaran', 'detailTransaksi', 'tambahPoin']);
            return view('pelanggan.transaction-detail', compact('transaksi'));
        } catch (\Exception $e) {
            return view('error.error-page', [
                'page_name' => 'Halaman Detail Transaksi',
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
            ]);
        }
    }

    /**
     * Endpoint untuk kasir membuat akun user cepat dan menautkannya ke pelanggan.
     */
    public function kasirCreateAccount(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'pelanggan_id' => 'required|integer|exists:pelanggan,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:6',
        ]);

        $pelanggan = Pelanggan::where('id', $validated['pelanggan_id'])->first();

        $pelangganRole = Role::where('nama_role', 'pelanggan')->first();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'] ?? 'password'),
            'role_id' => $pelangganRole?->id ?? 4,
            'pelanggan_id' => $pelanggan->id,
        ]);

        return redirect()->back()->with('success', 'Akun pelanggan berhasil dibuat.');
    }

    public function editProfile()
    {
        $pelanggan = auth()->user()->pelanggan;

        return view('pelanggan.edit-profile', compact('pelanggan'));
    }

    public function updateProfile(Request $request)
    {
        $pelanggan = auth()->user()->pelanggan;

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        $pelanggan->update($validated);

        return redirect()->route('pelanggan.profile')->with('success', 'Profil berhasil diupdate');
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        $pelanggan->update($validated);
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diupdate');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus');
    }
}
