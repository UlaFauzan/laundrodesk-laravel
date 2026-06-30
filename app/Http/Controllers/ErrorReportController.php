<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ErrorReport;
use App\Models\Notifikasi;

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
            'page_name'     => 'required|string|max:255',
            'error_message' => 'nullable|string',
            'description'   => 'required|string|max:1000',
            'error_details' => 'nullable|string',
        ]);

        $user = auth()->user();
        $pelanggan = $user->pelanggan;

        if (!$pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Data pelanggan tidak ditemukan.'
            ], 422);
        }

        // Safely parse error_details JSON string to array
        // Gunakan ?? null agar tidak undefined array key saat field tidak dikirim
        $rawDetails = $validated['error_details'] ?? null;
        $errorDetails = null;

        if (!empty($rawDetails)) {
            $decoded = json_decode($rawDetails, true);
            $errorDetails = is_array($decoded) ? $decoded : null;
        }

        ErrorReport::create([
            'user_id'       => $user->id,
            'pelanggan_id'  => $pelanggan->id,
            'page_name'     => $validated['page_name'],
            'error_message' => $validated['error_message'] ?? null,
            'description'   => $validated['description'],
            'error_details' => $errorDetails,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan masalah berhasil dikirim ke admin. Terima kasih!'
        ]);
    }

    /**
     * Display the specified resource (Admin only - Detail via AJAX).
     */
    public function show(string $id)
    {
        $report = ErrorReport::with(['user', 'pelanggan'])->findOrFail($id);
        $notification = Notifikasi::where('error_report_id', $report->id)
            ->orderByDesc('created_at')
            ->first();

        return response()->json([
            'success' => true,
            'data'    => [
                'id'            => $report->id,
                'pelanggan_id'  => $report->pelanggan->id ?? '-',
                'nama'          => $report->pelanggan->nama ?? $report->user->name ?? '-',
                'page_name'     => $report->page_name,
                'error_message' => $report->error_message,
                'description'   => $report->description,
                'admin_note'    => $report->admin_note,
                'error_details' => $report->error_details,
                'resolved_at'   => $report->resolved_at
                    ? $report->resolved_at->format('d/m/Y H:i')
                    : null,
                'created_at'    => $report->created_at->format('d/m/Y H:i'),
                'notification_status' => $notification?->status_baca ?? 'Belum Ada Notifikasi',
            ],
        ]);
    }

    /**
     * Mark report as resolved (Admin only).
     */
    public function resolve(Request $request, string $id)
    {
        $report = ErrorReport::findOrFail($id);

        if ($report->resolved_at) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan sudah ditandai selesai sebelumnya.'
            ], 422);
        }

        $validated = $request->validate([
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $report->update([
            'resolved_at' => now(),
            'admin_note' => $validated['admin_note'] ?? null,
        ]);

        if ($report->pelanggan_id) {
            $adminNoteText = trim($validated['admin_note'] ?? '');
            $notificationMessage = 'Baik, terima kasih atas laporannya.';

            if ($adminNoteText !== '') {
                $notificationMessage .= ' Catatan dari admin: ' . $adminNoteText . '.';
            }

            $notificationMessage .= ' Silakan refresh halaman untuk melihat pembaruan.';

            Notifikasi::create([
                'pelanggan_id' => $report->pelanggan_id,
                'error_report_id' => $report->id,
                'pesan' => $notificationMessage,
                'status_baca' => 'Belum Dibaca',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil ditandai sebagai sudah ditangani.'
        ]);
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
