## 🚀 Quick Start Guide - Error Handling System

### ✅ Apa yang Sudah Diimplementasikan

Sistem error handling yang user-friendly untuk halaman riwayat transaksi pelanggan dengan:
1. **Error Page** - Menampilkan pesan error yang ramah (bukan error mentah)
2. **Report Modal** - Pelanggan bisa laporkan masalah ke admin
3. **Auto Data Capture** - Otomatis menangkap ID, Nama Pelanggan, Halaman
4. **Admin Dashboard** - Admin bisa lihat semua error reports

---

### 🎯 Fitur Utama

#### Untuk Pelanggan
- ✅ Halaman error yang ramah saat ada gangguan
- ✅ Tombol "Laporkan Masalah ke Admin" 
- ✅ Modal form untuk menulis deskripsi
- ✅ Data otomatis di-capture (ID, Nama, Halaman)
- ✅ Notifikasi sukses setelah submit

#### Untuk Admin
- ✅ Dashboard `/error-reports` untuk lihat semua laporan
- ✅ Status tracking (Pending/Resolved)
- ✅ Pagination (20 per page)
- ✅ Quick action buttons

---

### 📝 Database Table

Tabel `error_reports` sudah dibuat dengan columns:
```
- id (PK)
- user_id (FK)
- pelanggan_id (FK)
- page_name (nama halaman)
- error_message (pesan error)
- description (deskripsi dari pelanggan - max 1000 chars)
- error_details (JSON: timestamp, userAgent, url, referrer)
- resolved_at (timestamp untuk tracking resolved status)
- created_at, updated_at
```

---

### 🔗 URLs dan Routes

**Untuk Pelanggan:**
```
GET  /pelanggan/transactions           - Lihat riwayat transaksi
GET  /pelanggan/transactions/{id}      - Lihat detail transaksi
POST /error-reports                    - Submit error report (AJAX)
```

**Untuk Admin:**
```
GET  /error-reports                    - Lihat semua error reports
GET  /error-reports/{id}               - Lihat detail error report (future)
```

---

### 📊 Data yang Auto-Captured

Ketika pelanggan submit error report, system otomatis capture:

```json
{
  "user_id": 7,                          // dari auth()->user()->id
  "pelanggan_id": 5,                     // dari auth()->user()->pelanggan->id
  "page_name": "Halaman Riwayat Transaksi",
  "error_message": "PDOException: SQLSTATE[HY000]...",  // dari exception
  "description": "Halaman tidak bisa dimuat",           // dari user input
  "error_details": {                     // auto-captured via JavaScript
    "timestamp": "2026-06-19T10:30:45Z",
    "userAgent": "Mozilla/5.0...",
    "url": "http://127.0.0.1:8000/pelanggan/transactions",
    "referrer": ""
  },
  "created_at": "2026-06-19 10:30:45",
  "updated_at": "2026-06-19 10:30:45"
}
```

---

### 🧪 Testing

#### Test di Environment Local

1. **Test Error Page Display:**
   - Matikan database atau trigger connection error
   - Buka `/pelanggan/transactions`
   - Verifikasi: Error page ditampilkan (bukan error mentah)

2. **Test Report Submission:**
   - Di error page, klik "Laporkan Masalah ke Admin"
   - Modal terbuka dengan data auto-filled
   - Isi deskripsi: "Test error report"
   - Klik "Kirim Laporan"
   - Verifikasi: Success message muncul

3. **Test Admin Dashboard:**
   - Login sebagai admin
   - Buka `/error-reports`
   - Verifikasi: Error reports muncul di table
   - Klik "Resolved" button
   - Verifikasi: Status berubah

---

### 🎨 UI/UX Preview

#### Error Page
```
┌─────────────────────────────────────┐
│                                     │
│         ⚠️ Oops! Terjadi Kesalahan  │
│                                     │
│  Maaf, kami mengalami kesulitan     │
│  dalam memuat Halaman Riwayat       │
│  Transaksi...                       │
│                                     │
│  💡 Saran: Silakan refresh halaman │
│     atau hubungi admin...           │
│                                     │
│  [🔄 Refresh] [📞 Laporkan]        │
│                                     │
└─────────────────────────────────────┘
```

#### Report Modal
```
┌────────────────────────────────────┐
│ Laporkan Masalah ke Admin       ×   │
├────────────────────────────────────┤
│                                    │
│ ID Pelanggan: 5                   │
│ Nama: Andi Wijaya                 │
│ Halaman: Halaman Riwayat Transaksi│
│                                    │
│ Deskripsi Masalah: *              │
│ ┌──────────────────────────────┐  │
│ │ Jelaskan masalah...          │  │
│ │                              │  │
│ └──────────────────────────────┘  │
│ Maksimal 1000 karakter             │
│                                    │
│        [Batal] [✓ Kirim Laporan]  │
│                                    │
└────────────────────────────────────┘
```

#### Admin Dashboard
```
┌──────────────────────────────────────────────────────────┐
│ 📋 Laporan Masalah dari Pelanggan    Total: 5            │
├──────────────────────────────────────────────────────────┤
│ Tanggal │ ID  │ Nama   │ Halaman   │ Deskripsi │ Status  │
├─────────┼─────┼────────┼───────────┼───────────┼─────────┤
│ 19/06   │ 5   │ Andi   │ Riwayat   │ Tidak bisa│ ⏳Pending│
│ ...     │ ... │ ...    │ ...       │ ...       │ ...     │
└──────────────────────────────────────────────────────────┘
```

---

### 🔐 Security

- ✅ CSRF Protection via @csrf
- ✅ Authentication required (middleware: auth)
- ✅ Role-based access (pelanggan, admin)
- ✅ Input validation (max length, required fields)
- ✅ Pelanggan hanya bisa submit report untuk dirinya sendiri

---

### 📂 Files Structure

```
laundry/
├── database/migrations/
│   ├── 2026_06_19_033221_create_error_reports_table.php
│   ├── 2026_06_19_033449_fix_pembayaran_table_columns.php
│
├── app/Models/
│   └── ErrorReport.php
│
├── app/Http/Controllers/
│   ├── ErrorReportController.php        (NEW)
│   └── PelangganController.php           (UPDATED)
│
├── resources/views/
│   ├── error/
│   │   └── error-page.blade.php         (NEW)
│   └── error-reports/
│       └── index.blade.php               (NEW)
│
├── routes/
│   └── web.php                           (UPDATED)
│
└── ERROR_HANDLING_GUIDE.md               (NEW - full documentation)
```

---

### 🎓 Cara Pakai (Step by Step)

#### Untuk Dev/Admin Setup

1. **Database Migration sudah dijalankan:**
   ```bash
   php artisan migrate
   ```
   ✅ Table `error_reports` sudah dibuat

2. **Cek routes:**
   ```bash
   php artisan route:list | grep error
   ```
   Output:
   ```
   POST   /error-reports
   GET    /error-reports
   ```

3. **Cek views:**
   - `resources/views/error/error-page.blade.php`
   - `resources/views/error-reports/index.blade.php`

---

#### Untuk Pelanggan (End User)

1. **Normal flow:**
   - Buka halaman `/pelanggan/transactions`
   - Lihat daftar transaksi normal (jika tidak ada error)

2. **Saat ada error:**
   - Lihat halaman error yang ramah
   - Klik "Laporkan Masalah ke Admin"
   - Isi deskripsi singkat
   - Klik "Kirim Laporan"
   - Terima notifikasi sukses

---

#### Untuk Admin

1. **Lihat error reports:**
   - Login sebagai admin
   - Navigate ke `/error-reports`
   - Lihat semua laporan yang masuk

2. **Track status:**
   - Klik "Detail" untuk lihat informasi lengkap
   - Klik "Resolved" untuk mark sebagai selesai ditangani

3. **Follow up:**
   - Hubungi pelanggan via phone/WhatsApp
   - Perbaiki masalah yang terjadi

---

### ❓ FAQ

**Q: Bagaimana jika database error terjadi?**
A: Error di-catch oleh try-catch, halaman error ditampilkan, dan pelanggan bisa laporkan.

**Q: Apa yang disimpan di database?**
A: Error message, deskripsi pelanggan, timestamp, user agent, URL, referrer.

**Q: Apakah data sensitif tersimpan?**
A: Tidak, hanya data non-sensitif yang di-capture (timestamps, URLs, user agent).

**Q: Bagaimana cara mark error sebagai resolved?**
A: Admin klik tombol "Resolved" di admin dashboard.

**Q: Apakah bisa tambah error handling ke halaman lain?**
A: Ya, gunakan pola yang sama dengan try-catch di controller.

---

### 📞 Support

Untuk bantuan lebih lanjut, lihat file `ERROR_HANDLING_GUIDE.md` untuk dokumentasi lengkap.
