@extends('layout')

@section('content')
<div style="padding: 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap;">
        <h2 style="margin: 0 0 10px 0;">📋 Laporan Masalah dari Pelanggan</h2>
        <span style="background: #e7f3ff; padding: 8px 16px; border-radius: 4px; font-weight: bold; color: #0066cc;">
            Total: {{ $reports->total() }}
        </span>
    </div>

    @if ($reports->isEmpty())
        <div style="background: #f8f9fa; padding: 40px; text-align: center; border-radius: 8px;">
            <p style="color: #666; font-size: 16px;">Belum ada laporan masalah dari pelanggan.</p>
        </div>
    @else
        <!-- Cards View - More Responsive -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px; margin-bottom: 30px;">
            @foreach($reports as $report)
            <div style="background: white; border: 1px solid #dee2e6; border-radius: 8px; padding: 15px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                <!-- Header -->
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                    <div>
                        <strong style="color: #0066cc;">Pelanggan ID {{ $report->pelanggan->id }}</strong>
                        <div style="font-size: 12px; color: #666; margin-top: 2px;">{{ $report->pelanggan->nama ?? '-' }}</div>
                    </div>
                    @if($report->resolved_at)
                        <span style="background: #d4edda; color: #155724; padding: 2px 6px; border-radius: 3px; font-size: 11px; white-space: nowrap;">
                            ✓ OK
                        </span>
                    @else
                        <span style="background: #fff3cd; color: #856404; padding: 2px 6px; border-radius: 3px; font-size: 11px; white-space: nowrap;">
                            ⏳ Pending
                        </span>
                    @endif
                </div>

                <!-- Halaman -->
                <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #f0f0f0;">
                    <small style="color: #666;">Halaman:</small>
                    <div style="background: #e7f3ff; padding: 4px 6px; border-radius: 3px; font-size: 12px; margin-top: 4px;">
                        {{ $report->page_name }}
                    </div>
                </div>

                <!-- Deskripsi -->
                <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #f0f0f0;">
                    <small style="color: #666;">Deskripsi:</small>
                    <div style="font-size: 13px; margin-top: 4px; line-height: 1.4;">
                        {{ substr($report->description, 0, 100) }}{{ strlen($report->description) > 100 ? '...' : '' }}
                    </div>
                </div>

                <!-- Tanggal -->
                <div style="margin-bottom: 12px;">
                    <small style="color: #999;">{{ $report->created_at->format('d/m/Y H:i') }}</small>
                </div>

                <!-- Actions -->
                <div style="display: flex; gap: 6px;">
                    <button onclick="viewDetail({{ $report->id }})" style="flex: 1; background: #0066cc; color: white; border: none; padding: 6px; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: bold;">
                        📋 Detail
                    </button>
                    @if(!$report->resolved_at)
                        <button onclick="markResolved({{ $report->id }})" style="flex: 1; background: #28a745; color: white; border: none; padding: 6px; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: bold;">
                            ✓ OK
                        </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div style="display: flex; justify-content: center; margin-top: 20px;">
            {{ $reports->links() }}
        </div>
    @endif
</div>

<!-- Detail Modal -->
<div id="detailModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 8px; max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h4>Detail Laporan Masalah</h4>
            <button onclick="closeDetailModal()" style="background: none; border: none; font-size: 24px; cursor: pointer;">×</button>
        </div>

        <div id="detailContent" style="background: #f8f9fa; padding: 20px; border-radius: 4px;">
            <!-- Content loaded via AJAX -->
        </div>

        <div style="margin-top: 20px; text-align: right;">
            <button onclick="closeDetailModal()" style="padding: 10px 20px; border: 1px solid #ddd; background: white; border-radius: 4px; cursor: pointer;">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
function viewDetail(reportId) {
    // In a real app, you would fetch the details via AJAX
    // For now, just show a simple message
    const modal = document.getElementById('detailModal');
    const content = document.getElementById('detailContent');
    
    content.innerHTML = `
        <p><strong>Loading detail...</strong></p>
    `;
    
    modal.style.display = 'flex';
}

function closeDetailModal() {
    document.getElementById('detailModal').style.display = 'none';
}

function markResolved(reportId) {
    if (confirm('Tandai laporan ini sebagai sudah ditangani?')) {
        // In a real app, you would send an AJAX request here
        alert('Status updated to resolved');
        location.reload();
    }
}

// Close modal when clicking outside
document.getElementById('detailModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDetailModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDetailModal();
    }
});
</script>
@endsection
