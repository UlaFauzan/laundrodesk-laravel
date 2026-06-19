@extends('layout')

@section('content')
<div class="error-container" style="display: flex; align-items: center; justify-content: center; min-height: calc(100vh - 200px);">
    <div class="error-content" style="text-align: center; background: #f8f9fa; padding: 40px; border-radius: 8px; max-width: 500px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="font-size: 64px; margin-bottom: 20px;">⚠️</div>
        
        <h2 style="color: #dc3545; margin-bottom: 15px;">Oops! Terjadi Kesalahan</h2>
        
        <p style="color: #666; font-size: 16px; margin-bottom: 25px;">
            Maaf, kami mengalami kesulitan dalam memuat <strong>{{ $page_name }}</strong>. 
            Hal ini mungkin disebabkan oleh gangguan jaringan atau masalah database sementara.
        </p>

        <div style="background: #e7f3ff; border-left: 4px solid #0066cc; padding: 15px; margin-bottom: 25px; text-align: left; border-radius: 4px;">
            <p style="margin: 0; color: #0066cc; font-size: 14px;">
                <strong>💡 Saran:</strong> Silakan coba refresh halaman atau hubungi admin jika masalah berlanjut.
            </p>
        </div>

        <button class="btn btn-primary" onclick="location.reload()" style="margin-right: 10px; padding: 10px 20px; font-size: 16px; cursor: pointer;">
            🔄 Refresh Halaman
        </button>

        <button class="btn btn-warning" onclick="openErrorReportModal()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; background-color: #ffc107; color: #000; border: none; border-radius: 4px;">
            📞 Laporkan Masalah ke Admin
        </button>

        <p style="color: #999; font-size: 12px; margin-top: 25px;">
            Error ID: {{ uniqid() }}
        </p>
    </div>
</div>

<!-- Modal Laporan Masalah -->
<div id="errorReportModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 8px; max-width: 500px; width: 90%; max-height: 80vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h4>Laporkan Masalah ke Admin</h4>
            <button onclick="closeErrorReportModal()" style="background: none; border: none; font-size: 24px; cursor: pointer;">×</button>
        </div>

        <form id="errorReportForm">
            @csrf
            <input type="hidden" name="page_name" value="{{ $page_name }}">
            <input type="hidden" name="error_message" value="{{ $error_message ?? '' }}">
            <input type="hidden" name="error_details" id="error_details" value="">

            <!-- Info Pelanggan (Read-only) -->
            <div style="background: #f0f0f0; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                <p style="margin: 5px 0; font-size: 14px;">
                    <strong>ID Pelanggan:</strong> {{ auth()->user()->pelanggan->id ?? '-' }}
                </p>
                <p style="margin: 5px 0; font-size: 14px;">
                    <strong>Nama:</strong> {{ auth()->user()->pelanggan->nama ?? auth()->user()->name }}
                </p>
                <p style="margin: 5px 0; font-size: 14px;">
                    <strong>Halaman:</strong> {{ $page_name }}
                </p>
            </div>

            <!-- Description Field -->
            <div style="margin-bottom: 20px;">
                <label for="description" style="display: block; margin-bottom: 8px; font-weight: bold;">
                    Deskripsi Masalah <span style="color: red;">*</span>
                </label>
                <textarea 
                    id="description" 
                    name="description" 
                    required 
                    placeholder="Jelaskan masalah yang Anda alami..."
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-family: Arial, sans-serif; resize: vertical; min-height: 100px;"
                ></textarea>
                <small style="color: #999; display: block; margin-top: 5px;">Maksimal 1000 karakter</small>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeErrorReportModal()" style="padding: 10px 20px; border: 1px solid #ddd; background: white; border-radius: 4px; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    ✓ Kirim Laporan
                </button>
            </div>
        </form>

        <div id="successMessage" style="display: none; color: #28a745; margin-top: 15px; padding: 10px; background: #f0f8f0; border-radius: 4px;">
            ✓ Laporan berhasil dikirim! Terima kasih atas informasinya.
        </div>
    </div>
</div>

<script>
function openErrorReportModal() {
    document.getElementById('errorReportModal').style.display = 'flex';
    document.getElementById('description').focus();
}

function closeErrorReportModal() {
    document.getElementById('errorReportModal').style.display = 'none';
    document.getElementById('errorReportForm').reset();
    document.getElementById('successMessage').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('errorReportModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeErrorReportModal();
    }
});

// Handle form submission
document.getElementById('errorReportForm')?.addEventListener('submit', function(e) {
    e.preventDefault();

    // Store error details as JSON
    const errorDetails = JSON.stringify({
        timestamp: new Date().toISOString(),
        userAgent: navigator.userAgent,
        url: window.location.href,
        referrer: document.referrer,
    });

    const formData = new FormData(this);
    formData.set('error_details', errorDetails);

    fetch('{{ route("error-reports.store") }}', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('errorReportForm').style.display = 'none';
            document.getElementById('successMessage').style.display = 'block';
            
            setTimeout(() => {
                closeErrorReportModal();
                document.getElementById('errorReportForm').style.display = 'block';
            }, 3000);
        } else {
            alert('Error: ' + (data.message || 'Terjadi kesalahan saat mengirim laporan.'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    });
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeErrorReportModal();
    }
});
</script>

<style>
.error-container {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.btn {
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-warning:hover {
    background-color: #e0a800;
}
</style>
@endsection
