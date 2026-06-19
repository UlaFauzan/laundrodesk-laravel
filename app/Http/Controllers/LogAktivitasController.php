<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogAktivitas;

class LogAktivitasController extends Controller
{
    public function index()
    {
        $log = LogAktivitas::orderBy('waktu', 'desc')->get();
        return view('log_aktivitas.index', compact('log'));
    }

    public function show(LogAktivitas $logAktivitas)
    {
        return view('log_aktivitas.show', compact('logAktivitas'));
    }

    public function destroy(LogAktivitas $logAktivitas)
    {
        $logAktivitas->delete();
        return redirect()->route('log-aktivitas.index')->with('success', 'Log berhasil dihapus');
    }
}
