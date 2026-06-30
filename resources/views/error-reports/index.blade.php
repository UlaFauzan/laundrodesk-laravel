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
                        <button onclick="openResolveModal({{ $report->id }})" style="flex: 1; background: #28a745; color: white; border: none; padding: 6px; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: bold;">
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

<!-- Resolve Modal -->
<div id="resolveModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 8px; max-width: 600px; width: 90%;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h4>Konfirmasi Penyelesaian Laporan</h4>
            <button onclick="closeResolveModal()" style="background: none; border: none; font-size: 24px; cursor: pointer;">×</button>
        </div>

        <input type="hidden" id="resolveReportId" value="">

        <div style="margin-bottom: 15px;">
            <label for="adminNote" style="display: block; font-weight: bold; margin-bottom: 8px;">Keterangan Admin (opsional):</label>
            <textarea id="adminNote" style="width: 100%; min-height: 120px; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;" placeholder="Tulis keterangan tambahan di sini..."></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px;">
            <button onclick="closeResolveModal()" style="padding: 10px 20px; border: 1px solid #ddd; background: white; border-radius: 4px; cursor: pointer;">
                Batal
            </button>
            <button onclick="submitResolve()" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Simpan & Selesaikan
            </button>
        </div>
    </div>
</div>

<script>
function viewDetail(reportId) {
    const modal = document.getElementById('detailModal');
    const content = document.getElementById('detailContent');
    
    content.innerHTML = `
        <p><strong>Memuat detail...</strong></p>
    `;
    
    modal.style.display = 'flex';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    fetch(`/error-reports/${reportId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        }
    })
    .then(async (response) => {
        const data = await response.json().catch(() => ({}));

        if (!response.ok || !data.success) {
            throw new Error(data.message || 'Gagal memuat detail laporan.');
        }

        const detail = data.data || {};
        content.innerHTML = `
            <div style="display: grid; gap: 10px;">
                <div><strong>Nama Pelanggan:</strong> ${detail.nama || '-'}</div>
                <div><strong>Halaman:</strong> ${detail.page_name || '-'}</div>
                <div><strong>Pesan Laporan Pelanggan:</strong> ${detail.description || '-'}</div>
                <div><strong>Informasi Error Teknis:</strong> ${detail.error_message || '-'}</div>
                <div><strong>Keterangan Admin:</strong> ${detail.admin_note || '-'}</div>
                <div><strong>Status Baca Pelanggan:</strong> <span style="font-weight: bold; color: ${detail.notification_status === 'Sudah Dibaca' ? '#155724' : '#856404'};">${detail.notification_status || 'Belum Ada Notifikasi'}</span></div>
                <div><strong>Dibuat:</strong> ${detail.created_at || '-'}</div>
                <div><strong>Diselesaikan:</strong> ${detail.resolved_at || '-'}</div>
            </div>
        `;
    })
    .catch((error) => {
        content.innerHTML = `<p style="color: red;">${error.message}</p>`;
    });
}

function closeDetailModal() {
    document.getElementById('detailModal').style.display = 'none';
}

function openResolveModal(reportId) {
    document.getElementById('resolveReportId').value = reportId;
    document.getElementById('adminNote').value = '';
    document.getElementById('resolveModal').style.display = 'flex';
}

function closeResolveModal() {
    document.getElementById('resolveModal').style.display = 'none';
}

function submitResolve() {
    const reportId = document.getElementById('resolveReportId').value;
    const adminNote = document.getElementById('adminNote').value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    fetch(`/error-reports/${reportId}/resolve`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ admin_note: adminNote })
    })
    .then(async (response) => {
        const data = await response.json().catch(() => ({}));

        if (!response.ok || !data.success) {
            throw new Error(data.message || 'Gagal menandai laporan sebagai selesai.');
        }

        alert(data.message || 'Laporan berhasil ditandai sebagai sudah ditangani.');
        location.reload();
    })
    .catch((error) => {
        alert(error.message || 'Terjadi kesalahan saat mengubah status laporan.');
    });
}

// Close modal when clicking outside
document.getElementById('detailModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDetailModal();
    }
});

// Close modal when clicking outside resolve modal
document.getElementById('resolveModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeResolveModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDetailModal();
        closeResolveModal();
    }
});
</script>
@endsection
