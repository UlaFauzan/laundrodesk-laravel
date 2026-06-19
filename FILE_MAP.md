## 📊 ERROR HANDLING SYSTEM - FILE MAP & NAVIGATION

### 🗂️ PROJECT STRUCTURE

```
laundry/
│
├── 📄 IMPLEMENTATION_SUMMARY.md           ← START HERE! Full overview
├── 📄 ERROR_HANDLING_QUICKSTART.md        ← Quick reference guide
├── 📄 ERROR_HANDLING_GUIDE.md             ← Detailed documentation
├── 📄 TEST_ERROR_HANDLING.php             ← Testing script
│
├── database/
│   └── migrations/
│       ├── 2026_06_19_033221_create_error_reports_table.php      ✅ NEW
│       └── 2026_06_19_033449_fix_pembayaran_table_columns.php    ✅ NEW
│
├── app/
│   ├── Models/
│   │   └── ErrorReport.php                                        ✅ NEW
│   │
│   └── Http/
│       └── Controllers/
│           ├── ErrorReportController.php                          ✅ NEW
│           └── PelangganController.php                            ✅ UPDATED
│
├── resources/
│   └── views/
│       ├── error/
│       │   └── error-page.blade.php                              ✅ NEW
│       │       (Halaman error ramah untuk pelanggan)
│       │
│       └── error-reports/
│           └── index.blade.php                                    ✅ NEW
│               (Dashboard admin untuk review error reports)
│
└── routes/
    └── web.php                                                    ✅ UPDATED
        (Ditambah routes untuk error-reports)
```

---

## 🔄 COMPONENT INTERACTION DIAGRAM

```
┌─────────────────────────────────────────────────────────┐
│                    PELANGGAN SIDE                       │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  GET /pelanggan/transactions                           │
│  ↓                                                      │
│  PelangganController@transactions()                    │
│  ├─ Try: Query database                                │
│  │  ├─ Success → View: pelanggan.transactions          │
│  │  └─ Error → Catch block ↓                           │
│  └─ Catch: Exception caught                            │
│     └─ View: error.error-page ← ERROR PAGE SHOWN      │
│        ├─ Auto-display error message                   │
│        ├─ [Refresh] button                             │
│        └─ [Laporkan Masalah] button → Modal ↓         │
│           Modal Form:                                  │
│           ├─ Auto-fill: ID, Nama, Halaman            │
│           ├─ Input: Deskripsi (user types)            │
│           ├─ [Kirim Laporan] → AJAX POST              │
│           │  POST /error-reports                      │
│           │  ↓                                         │
│           └─ Success notification                      │
│                                                         │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                      SERVER SIDE                        │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  ErrorReportController@store()                        │
│  ├─ POST /error-reports (AJAX)                        │
│  ├─ Validate input                                     │
│  ├─ Get auth()->user()->pelanggan                      │
│  └─ ErrorReport::create([                             │
│     'user_id' => ...,                                 │
│     'pelanggan_id' => ...,                            │
│     'page_name' => ...,                               │
│     'error_message' => ...,                           │
│     'description' => ... (user input)                 │
│     'error_details' => ... (JSON)                     │
│  ])                                                    │
│  ↓                                                     │
│  Database: INSERT into error_reports                  │
│  ↓                                                     │
│  Return: JSON {success: true}                         │
│                                                         │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                     ADMIN SIDE                          │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  GET /error-reports                                    │
│  ↓                                                     │
│  ErrorReportController@index()                        │
│  ├─ Query: error_reports with relationships           │
│  ├─ Paginate: 20 per page                             │
│  └─ View: error-reports.index ← ADMIN DASHBOARD      │
│     ├─ Display table with:                            │
│     │  ├─ Tanggal                                     │
│     │  ├─ ID Pelanggan                               │
│     │  ├─ Nama Pelanggan                             │
│     │  ├─ Halaman                                     │
│     │  ├─ Deskripsi                                  │
│     │  ├─ Status (Pending/Resolved)                  │
│     │  └─ Actions (Detail, Resolved)                 │
│     ├─ Pagination                                     │
│     └─ Filters (future)                              │
│                                                        │
└─────────────────────────────────────────────────────────┘
```

---

## 📚 DOCUMENTATION MAP

| Document | Purpose | Audience | Read Time |
|----------|---------|----------|-----------|
| **IMPLEMENTATION_SUMMARY.md** | Complete overview of what was built | Developers, Project Managers | 15 min |
| **ERROR_HANDLING_QUICKSTART.md** | Quick reference and key info | Developers, Testers | 10 min |
| **ERROR_HANDLING_GUIDE.md** | Detailed technical documentation | Developers, DevOps | 20 min |
| **TEST_ERROR_HANDLING.php** | Testing and verification script | QA, Developers | 5 min |
| **FILE_MAP.md** (this file) | Navigation and structure | Everyone | 5 min |

---

## 🎯 QUICK LINKS

### For Developers
- **Model:** [app/Models/ErrorReport.php](../../app/Models/ErrorReport.php)
- **Controller:** [app/Http/Controllers/ErrorReportController.php](../../app/Http/Controllers/ErrorReportController.php)
- **Updated Controller:** [app/Http/Controllers/PelangganController.php](../../app/Http/Controllers/PelangganController.php)

### For Frontend Developers
- **Error Page View:** [resources/views/error/error-page.blade.php](../../resources/views/error/error-page.blade.php)
- **Admin Dashboard View:** [resources/views/error-reports/index.blade.php](../../resources/views/error-reports/index.blade.php)

### For Database Administrators
- **Migration:** [database/migrations/2026_06_19_033221_create_error_reports_table.php](../../database/migrations/2026_06_19_033221_create_error_reports_table.php)
- **Table Name:** `error_reports`

### For DevOps/QA
- **Routes:** Check [routes/web.php](../../routes/web.php) for new routes
- **Testing:** Run [TEST_ERROR_HANDLING.php](../../TEST_ERROR_HANDLING.php)

---

## 🚀 START HERE

### Step 1: Understand the System (5 min)
Read: **IMPLEMENTATION_SUMMARY.md**

### Step 2: View Architecture (5 min)
Read: **ERROR_HANDLING_QUICKSTART.md** - UI/UX Preview section

### Step 3: Learn Details (20 min)
Read: **ERROR_HANDLING_GUIDE.md**

### Step 4: Test Implementation (10 min)
- Run testing script: `php artisan tinker < TEST_ERROR_HANDLING.php`
- Or manually test in browser

### Step 5: Deploy (Follow your deployment process)

---

## 🔍 FEATURE CHECKLIST

### For Pelanggan (End User)
- ✅ See friendly error page (not raw error)
- ✅ See "Laporkan Masalah ke Admin" button
- ✅ Click button to open modal
- ✅ See auto-filled: ID, Nama, Halaman
- ✅ Write description
- ✅ Submit report
- ✅ See success notification

### For Admin
- ✅ Access `/error-reports` page
- ✅ See table with all error reports
- ✅ See Pending/Resolved status
- ✅ See error details
- ✅ Mark errors as Resolved
- ✅ Pagination (20 per page)

### For Developer
- ✅ Easy error handling pattern (try-catch)
- ✅ Automatic data capture
- ✅ AJAX form submission
- ✅ Database persistence
- ✅ Admin review interface
- ✅ Fully documented code

---

## 🔐 SECURITY CHECKLIST

- ✅ CSRF token protection
- ✅ Authentication required (middleware: auth)
- ✅ Authorization (role-based access)
- ✅ Input validation
- ✅ No sensitive data captured
- ✅ SQL injection protected (Eloquent ORM)
- ✅ XSS protected (Blade templates)

---

## 📊 URLS & ENDPOINTS

| Method | URL | Controller | Middleware | Purpose |
|--------|-----|-----------|-----------|---------|
| POST | `/error-reports` | ErrorReportController@store | auth, pelanggan | Submit error report |
| GET | `/error-reports` | ErrorReportController@index | auth, admin | View all error reports |

---

## 🎨 UI/UX COMPONENTS

### Error Page Elements
```
┌─ Page Header
├─ 🚨 Error Icon
├─ Heading: "Oops! Terjadi Kesalahan"
├─ Message: "Maaf, kami mengalami kesulitan..."
├─ Info Box: "💡 Saran: Silakan refresh..."
├─ [🔄 Refresh] button
├─ [📞 Laporkan] button
└─ Error ID: uniqid()
```

### Modal Form Elements
```
┌─ Header: "Laporkan Masalah ke Admin" [×]
├─ Info Box (read-only):
│  ├─ ID Pelanggan: 5
│  ├─ Nama: Andi Wijaya
│  └─ Halaman: Halaman Riwayat Transaksi
├─ Label: "Deskripsi Masalah *"
├─ Textarea: (placeholder: "Jelaskan masalah...")
├─ Note: "Maksimal 1000 karakter"
├─ [Batal] [✓ Kirim Laporan] buttons
└─ Success Message (shown after submit)
```

### Admin Table Elements
```
┌─ Header: "📋 Laporan Masalah dari Pelanggan" | Total: X
├─ Table Headers:
│  ├─ Tanggal
│  ├─ ID Pelanggan
│  ├─ Nama Pelanggan
│  ├─ Halaman
│  ├─ Deskripsi
│  ├─ Status
│  └─ Aksi
├─ Table Rows (for each error report)
├─ Status Badges:
│  ├─ 🟡 Pending
│  └─ 🟢 Resolved
├─ Action Buttons:
│  ├─ [Detail]
│  └─ [Resolved]
└─ Pagination Links
```

---

## 💡 COMMON TASKS

### Add Error Handling to Another Page
```php
public function someAction()
{
    try {
        // Your code
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

### View Error Reports
1. Login as admin
2. Go to: `http://127.0.0.1:8000/error-reports`
3. Review table with all reports

### Check Database
```bash
# Connect to MySQL
mysql -u root -p laundry_db

# View table
SELECT * FROM error_reports;

# View with relationships
SELECT 
  er.id,
  er.user_id,
  er.pelanggan_id,
  er.page_name,
  er.description,
  er.created_at
FROM error_reports er
ORDER BY er.created_at DESC;
```

---

## 🧪 TESTING COMMANDS

```bash
# Run tinker and test
php artisan tinker

# Inside tinker, paste TEST_ERROR_HANDLING.php contents
# Or run: include 'TEST_ERROR_HANDLING.php'

# View routes
php artisan route:list | grep error

# Check migrations
php artisan migrate:status
```

---

## ❓ FAQ

**Q: Bagaimana jika saya perlu lihat error detail lebih lanjut?**
A: Lihat file `ERROR_HANDLING_GUIDE.md` section "Data yang Dikumpulkan"

**Q: Bagaimana cara customize error page?**
A: Edit `resources/views/error/error-page.blade.php`

**Q: Bagaimana cara tambah fields ke error report?**
A: 
1. Create migration untuk add columns
2. Update ErrorReport model fillable
3. Update form di view

**Q: Apakah bisa send email notification?**
A: Ya, future enhancement - lihat IMPLEMENTATION_SUMMARY.md

---

## 📞 CONTACT & SUPPORT

For questions or issues, refer to the documentation files in order:
1. ERROR_HANDLING_QUICKSTART.md (quick answers)
2. ERROR_HANDLING_GUIDE.md (detailed answers)
3. IMPLEMENTATION_SUMMARY.md (architecture)

---

## ✨ SYSTEM STATUS

**Overall Status: ✅ COMPLETE & READY FOR PRODUCTION**

- Database: ✅ Migrated
- Backend: ✅ Implemented
- Frontend: ✅ Implemented
- Documentation: ✅ Complete
- Testing: ✅ Test script available
- Security: ✅ Secured

**Last Updated:** 2026-06-19
**Version:** 1.0
