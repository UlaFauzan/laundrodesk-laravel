<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ErrorReport;

class ErrorReportController extends Controller
{
    /**
     * Display a listing of the resource (Admin only).
     */
    public function index()
    {
        $reports = ErrorReport::with(['user', 'pelanggan'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('error-reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'page_name' => 'required|string|max:255',
            'error_message' => 'nullable|string',
            'description' => 'required|string|max:1000',
            'error_details' => 'nullable|string',  // Accept as string, we'll parse it
        ]);

        $user = auth()->user();
        $pelanggan = $user->pelanggan;

        if (!$pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Data pelanggan tidak ditemukan.'
            ], 422);
        }

        // Parse error_details JSON string to array
        $errorDetails = [];
        if ($validated['error_details']) {
            try {
                $errorDetails = json_decode($validated['error_details'], true);
                if (!is_array($errorDetails)) {
                    $errorDetails = [];
                }
            } catch (\Exception $e) {
                $errorDetails = [];
            }
        }

        $errorReport = ErrorReport::create([
            'user_id' => $user->id,
            'pelanggan_id' => $pelanggan->id,
            'page_name' => $validated['page_name'],
            'error_message' => $validated['error_message'],
            'description' => $validated['description'],
            'error_details' => $errorDetails,  // Saved as array
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan masalah berhasil dikirim ke admin. Terima kasih!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
