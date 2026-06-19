## 📋 Error Handling System - Dokumentasi Lengkap

### 🎯 Tujuan
Ketika halaman riwayat transaksi gagal dimuat (karena error jaringan, database, atau error lainnya), sistem akan:
1. Menampilkan halaman error yang ramah pengguna (bukan error mentah)
2. Menyediakan tombol "Laporkan Masalah ke Admin"
3. Memungkinkan pelanggan menulis deskripsi singkat tentang masalah
4. Otomatis menangkap dan menyimpan:
   - ID Pelanggan
   - Nama Pelanggan
   - Halaman tempat error terjadi
   - Error message dari system
   - Detail tambahan (timestamp, user agent, URL, referrer)

---

## 🏗️ Struktur Implementasi

### 1. Database Table: `error_reports`
```
Columns:
├── id (primary key)
├── user_id (FK ke users table)
├── pelanggan_id (FK ke pelanggan table)
├── page_name (nama halaman - misal: "Halaman Riwayat Transaksi")
├── error_message (pesan error dari system)
├── description (deskripsi dari pelanggan - max 1000 karakter)
├── error_details (JSON - menyimpan timestamp, userAgent, url, referrer)
├── resolved_at (timestamp ketika admin mark as resolved)
├── created_at & updated_at (automatic timestamps)
```

### 2. Model: `ErrorReport`
**File:** `app/Models/ErrorReport.php`

Relationships:
- `user()` - belongs to User model
- `pelanggan()` - belongs to Pelanggan model

Casts:
- `error_details` -> array (otomatis convert JSON)
- `resolved_at` -> datetime

### 3. Controller: `ErrorReportController`
**File:** `app/Http/Controllers/ErrorReportController.php`

#### Method: `store()`
- **Route:** POST `/error-reports`
- **Middleware:** auth, role:pelanggan
- **Input Validation:**
  - `page_name` - required string, max 255 chars
  - `error_message` - nullable string
  - `description` - required string, max 1000 chars
  - `error_details` - nullable array
- **Response:** JSON
  ```json
  {
    "success": true,
    "message": "Laporan masalah berhasil dikirim ke admin. Terima kasih!"
  }
  ```

#### Method: `index()`
- **Route:** GET `/error-reports`
- **Middleware:** auth, role:admin
- **Return:** View dengan list semua error reports (paginated 20 per page)

### 4. Views

#### Error Page: `resources/views/error/error-page.blade.php`
Ditampilkan saat ada error di halaman transaksi.

**Features:**
- ✅ User-friendly error message
- ✅ Tombol "🔄 Refresh Halaman" - untuk retry
- ✅ Tombol "📞 Laporkan Masalah ke Admin" - buka modal
- ✅ Modal form dengan:
  - Info pelanggan (read-only): ID, Nama, Halaman
  - Textarea untuk deskripsi
  - Tombol "Kirim Laporan"
- ✅ AJAX submission - form di-submit tanpa refresh
- ✅ Success message setelah submit berhasil

**Auto-captured Data:**
```javascript
{
  "timestamp": "2026-06-19T10:30:45.123Z",
  "userAgent": "Mozilla/5.0 ...",
  "url": "http://127.0.0.1:8000/pelanggan/transactions",
  "referrer": ""
}
```

#### Admin Dashboard: `resources/views/error-reports/index.blade.php`
View untuk admin melihat semua error reports.

**Features:**
- ✅ Tabel dengan kolom:
  - Tanggal
  - ID Pelanggan
  - Nama Pelanggan
  - Halaman
  - Deskripsi (preview)
  - Status (Pending/Resolved)
  - Aksi (Detail, Resolved button)
- ✅ Pagination (20 per page)
- ✅ Status badge:
  - 🟡 Pending (belum ditangani)
  - 🟢 Resolved (sudah ditangani)

### 5. Error Handling dalam Controller
**File:** `app/Http/Controllers/PelangganController.php`

#### Method: `transactions()`
```php
try {
    // Query transaksi
    // Return view 'pelanggan.transactions'
} catch (\Exception $e) {
    return view('error.error-page', [
        'page_name' => 'Halaman Riwayat Transaksi',
        'error_message' => $e->getMessage(),
        'error_code' => $e->getCode(),
    ]);
}
```

#### Method: `showTransaction()`
```php
try {
    // Query detail transaksi
    // Return view 'pelanggan.transaction-detail'
} catch (\Exception $e) {
    return view('error.error-page', [
        'page_name' => 'Halaman Detail Transaksi',
        'error_message' => $e->getMessage(),
        'error_code' => $e->getCode(),
    ]);
}
```

---

## 🚀 Bagaimana Cara Kerjanya

### Skenario 1: Normal Flow (Tanpa Error)
```
1. Pelanggan buka halaman /pelanggan/transactions
2. PelangganController@transactions() dijalankan
3. Database query berhasil
4. View 'pelanggan.transactions' di-render
5. Pelanggan melihat daftar transaksi normal
```

### Skenario 2: Ada Error (Database/Network)
```
1. Pelanggan buka halaman /pelanggan/transactions
2. PelangganController@transactions() dijalankan
3. Exception terjadi saat query database
4. Exception di-catch oleh try-catch block
5. View 'error.error-page' di-render dengan konteks error
6. Pelanggan melihat:
   - 🚨 Pesan error yang ramah: "Maaf, kami mengalami kesulitan..."
   - 🔄 Tombol "Refresh Halaman"
   - 📞 Tombol "Laporkan Masalah ke Admin"
```

### Skenario 3: Pelanggan Laporkan Error
```
1. Pelanggan melihat halaman error
2. Klik tombol "Laporkan Masalah ke Admin"
3. Modal form terbuka dengan data auto-filled:
   - ID Pelanggan: 5
   - Nama: Andi Wijaya
   - Halaman: Halaman Riwayat Transaksi
4. Pelanggan menulis deskripsi: "Halaman tidak bisa dimuat dari tadi pagi"
5. Klik tombol "Kirim Laporan"
6. Form di-submit via AJAX
7. Data disimpan ke database error_reports:
   - user_id: 7
   - pelanggan_id: 5
   - page_name: "Halaman Riwayat Transaksi"
   - error_message: "PDOException: SQLSTATE..."
   - description: "Halaman tidak bisa dimuat dari tadi pagi"
   - error_details: {timestamp, userAgent, url, referrer}
   - created_at: 2026-06-19 10:30:45
8. Pelanggan melihat success message: "✓ Laporan berhasil dikirim!"
9. Modal tutup otomatis setelah 3 detik
```

### Skenario 4: Admin Review Error Reports
```
1. Admin login ke sistem
2. Buka URL /error-reports (atau dari dashboard menu)
3. Melihat tabel dengan semua error reports:
   - Sorted by created_at (terbaru dulu)
   - Status: Pending/Resolved
   - Total: X reports
4. Admin bisa:
   - Klik "Detail" untuk melihat error details lengkap
   - Klik "Resolved" untuk mark sebagai sudah ditangani
5. Admin bisa follow up dengan pelanggan via phone/WhatsApp
6. Mark error sebagai resolved setelah diperbaiki
```

---

## 📝 Routes

### Untuk Pelanggan (authenticated, role:pelanggan)
```
POST /error-reports
└─ ErrorReportController@store
└─ Menyimpan error report
```

### Untuk Admin (authenticated, role:admin)
```
GET /error-reports
└─ ErrorReportController@index
└─ Melihat list semua error reports

GET /error-reports/{id}
└─ ErrorReportController@show
└─ Melihat detail error report (future implementation)
```

---

## 🔧 Implementasi Detail

### Migration
**File:** `database/migrations/2026_06_19_033221_create_error_reports_table.php`

```php
Schema::create('error_reports', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('pelanggan_id')->constrained('pelanggan')->cascadeOnDelete();
    $table->string('page_name'); 
    $table->text('error_message')->nullable();
    $table->text('description')->nullable();
    $table->json('error_details')->nullable();
    $table->timestamp('resolved_at')->nullable();
    $table->timestamps();
});
```

### Error Page - JavaScript
Form submission via AJAX:
```javascript
fetch('/error-reports', {
    method: 'POST',
    body: formData,
    headers: {
        'Accept': 'application/json',
    }
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        // Show success message
        // Hide form
        // Auto-close modal after 3 seconds
    }
});
```

---

## ✅ Checklist Implementasi

- ✅ Migration created: `error_reports` table
- ✅ Model created: `ErrorReport`
- ✅ Controller created: `ErrorReportController` dengan method `store()` dan `index()`
- ✅ Views created:
  - `resources/views/error/error-page.blade.php` (error handling page)
  - `resources/views/error-reports/index.blade.php` (admin dashboard)
- ✅ Error handling added ke:
  - `PelangganController@transactions()`
  - `PelangganController@showTransaction()`
- ✅ Routes configured:
  - `POST /error-reports` (pelanggan)
  - `GET /error-reports` (admin)
- ✅ Migration executed: Table created successfully

---

## 🔐 Security Considerations

1. **Validation:** Semua input di-validate:
   - `page_name` dan `description` tidak boleh kosong
   - `description` dibatasi max 1000 karakter
   - CSRF token required (via @csrf)

2. **Authentication:** Hanya authenticated users (pelanggan) yang bisa submit error reports
   - Middleware: `auth`, `role:pelanggan`
   - Otomatis menggunakan `auth()->user()->pelanggan`

3. **Authorization:** Hanya admin yang bisa melihat error reports
   - Route protected dengan `middleware(['auth', 'role:admin'])`

4. **Data:** Error details disimpan sebagai JSON
   - Tidak menyimpan sensitive data (passwords, tokens)
   - Hanya menyimpan: timestamp, userAgent, url, referrer

---

## 📊 Data yang Dikumpulkan

### Otomatis oleh System
- User ID (dari auth session)
- Pelanggan ID (dari user.pelanggan)
- Nama Pelanggan (dari pelanggan.nama)
- Error message dari exception
- Halaman tempat error terjadi

### Dari User Input
- Deskripsi singkat (textarea - max 1000 chars)

### Dari Browser (JavaScript)
- Timestamp (ISO format)
- User Agent
- Current URL
- Referrer

---

## 🎨 UI/UX Features

### Error Page
- 🎯 Central alignment
- 🚨 Warning icon (⚠️)
- 💬 Friendly error message
- 🟡 Info box dengan suggestion
- 🔄 Refresh button
- 📞 Report button

### Modal Form
- 📋 Auto-filled pelanggan info (read-only)
- ✍️ Textarea untuk deskripsi
- ✅ Submit button
- ❌ Cancel button
- 🎯 Keyboard support (Escape key to close)

### Admin Dashboard
- 📊 Table dengan sortable columns
- 🟡 Status badges (Pending/Resolved)
- 🔗 Action buttons (Detail, Resolved)
- 📄 Pagination

---

## 🚀 Testing

### Test Case 1: Happy Path (Error Report Submit)
```
1. Login sebagai pelanggan
2. Navigate ke /pelanggan/transactions
3. Trigger error (optional: DB query error)
4. Lihat error page
5. Klik "Laporkan Masalah ke Admin"
6. Isi deskripsi dan submit
7. Verify: Success message muncul
8. Verify: Data tersimpan di error_reports table
9. Login sebagai admin
10. Navigate ke /error-reports
11. Verify: Error report tampil di list
```

### Test Case 2: Form Validation
```
1. Open error report modal
2. Try submit tanpa deskripsi
3. Verify: Validation error ditampilkan
```

### Test Case 3: Admin Dashboard
```
1. Login sebagai admin
2. Navigate ke /error-reports
3. Verify: Table menampilkan semua error reports
4. Click "Detail" button
5. Verify: Detail modal terbuka
6. Click "Resolved" button
7. Verify: Status berubah menjadi "Resolved"
```

---

## 📚 Files Modified/Created

```
✅ Created:
├── database/migrations/2026_06_19_033221_create_error_reports_table.php
├── app/Models/ErrorReport.php
├── app/Http/Controllers/ErrorReportController.php
├── resources/views/error/error-page.blade.php
├── resources/views/error-reports/index.blade.php

✅ Modified:
├── routes/web.php (added ErrorReportController import & routes)
├── app/Http/Controllers/PelangganController.php (added try-catch)

🔧 Fixed:
├── database/migrations/2026_06_15_112517_add_missing_columns_to_pembayaran_table.php
├── database/migrations/2026_06_19_033449_fix_pembayaran_table_columns.php
```

---

## 🎓 Cara Menambah Error Handling ke Halaman Lain

Jika ingin menambah error handling ke halaman/controller lainnya, gunakan pola yang sama:

```php
public function someAction()
{
    try {
        // Your code here
        return view('some.view', $data);
    } catch (\Exception $e) {
        return view('error.error-page', [
            'page_name' => 'Halaman Nama Tertentu',
            'error_message' => $e->getMessage(),
            'error_code' => $e->getCode(),
        ]);
    }
}
```

---

## 🔄 Future Enhancements

- [ ] Email notification ke admin saat ada error report baru
- [ ] SMS notification ke admin
- [ ] Error trending & analytics dashboard
- [ ] Auto-resolve error setelah timeout tertentu
- [ ] Error categorization (Database, Network, etc.)
- [ ] Bulk export error reports
- [ ] Error rate threshold alerts
- [ ] Integration dengan external error tracking service (e.g., Sentry)

---

## 📞 Support

Jika ada pertanyaan atau perlu bantuan, silakan hubungi development team.
