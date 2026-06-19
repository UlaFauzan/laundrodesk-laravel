<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StatusLaundry;

class StatusLaundryController extends Controller
{
    public function index()
    {
        $status = StatusLaundry::all();
        return view('statuslaundry.index', compact('status'));
    }

    public function create()
    {
        return view('statuslaundry.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_status' => 'required|string|max:255|unique:status_laundry',
        ]);

        StatusLaundry::create($validated);
        return redirect()->route('status-laundry.index')->with('success', 'Status berhasil ditambahkan');
    }

    public function show(StatusLaundry $statusLaundry)
    {
        return view('statuslaundry.show', compact('statusLaundry'));
    }

    public function edit(StatusLaundry $statusLaundry)
    {
        return view('statuslaundry.edit', compact('statusLaundry'));
    }

    public function update(Request $request, StatusLaundry $statusLaundry)
    {
        $validated = $request->validate([
            'nama_status' => 'required|string|max:255|unique:status_laundry,nama_status,' . $statusLaundry->id,
        ]);

        $statusLaundry->update($validated);
        return redirect()->route('status-laundry.index')->with('success', 'Status berhasil diupdate');
    }

    public function destroy(StatusLaundry $statusLaundry)
    {
        $statusLaundry->delete();
        return redirect()->route('status-laundry.index')->with('success', 'Status berhasil dihapus');
    }
}