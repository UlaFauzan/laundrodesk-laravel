<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Notifikasi::with('pelanggan')->get();
        return view('notifikasi.index', compact('notifikasi'));
    }

    public function create()
    {
        $pelanggan = Pelanggan::all();
        return view('notifikasi.create', compact('pelanggan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'pesan' => 'required|string',
            'status_baca' => 'required|string|max:255',
        ]);

        Notifikasi::create($validated);
        return redirect()->route('notifikasi.index')->with('success', 'Notifikasi berhasil dibuat');
    }

    public function show(Notifikasi $notifikasi)
    {
        return view('notifikasi.show', compact('notifikasi'));
    }

    public function edit(Notifikasi $notifikasi)
    {
        $pelanggan = Pelanggan::all();
        return view('notifikasi.edit', compact('notifikasi', 'pelanggan'));
    }

    public function update(Request $request, Notifikasi $notifikasi)
    {
        $validated = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'pesan' => 'required|string',
            'status_baca' => 'required|string|max:255',
        ]);

        $notifikasi->update($validated);
        return redirect()->route('notifikasi.index')->with('success', 'Notifikasi berhasil diupdate');
    }

    public function destroy(Notifikasi $notifikasi)
    {
        $notifikasi->delete();
        return redirect()->route('notifikasi.index')->with('success', 'Notifikasi berhasil dihapus');
    }
}