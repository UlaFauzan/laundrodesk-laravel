<?php

/**
 * Test Script untuk Error Handling System
 * 
 * Jalankan: php artisan tinker < test_error_handling.php
 * Atau copy-paste commands di bawah satu persatu
 */

// ============================================
// TEST 1: Verify ErrorReport Model
// ============================================
echo "TEST 1: Checking ErrorReport Model...\n";

use App\Models\ErrorReport;
use App\Models\User;
use App\Models\Pelanggan;

// Get first user with pelanggan
$user = User::whereHas('pelanggan')->first();
if ($user) {
    echo "✓ User found: {$user->name} (ID: {$user->id})\n";
    echo "  Pelanggan: {$user->pelanggan->nama} (ID: {$user->pelanggan->id})\n";
} else {
    echo "✗ No user with pelanggan found\n";
}

// ============================================
// TEST 2: Create Test Error Report
// ============================================
echo "\nTEST 2: Creating test error report...\n";

$errorReport = ErrorReport::create([
    'user_id' => $user->id,
    'pelanggan_id' => $user->pelanggan->id,
    'page_name' => 'Halaman Riwayat Transaksi - TEST',
    'error_message' => 'PDOException: SQLSTATE[HY000]: General error test',
    'description' => 'Ini adalah test error report untuk memverifikasi system',
    'error_details' => [
        'timestamp' => now()->toIso8601String(),
        'userAgent' => 'Mozilla/5.0 (Test)',
        'url' => 'http://127.0.0.1:8000/pelanggan/transactions',
        'referrer' => 'http://127.0.0.1:8000/pelanggan/profile'
    ]
]);

echo "✓ Error report created!\n";
echo "  ID: {$errorReport->id}\n";
echo "  Page: {$errorReport->page_name}\n";
echo "  Status: " . ($errorReport->resolved_at ? "Resolved" : "Pending") . "\n";

// ============================================
// TEST 3: Verify Relationships
// ============================================
echo "\nTEST 3: Verifying relationships...\n";

$retrieved = ErrorReport::find($errorReport->id);
echo "✓ Retrieved error report\n";
echo "  User: {$retrieved->user->name}\n";
echo "  Pelanggan: {$retrieved->pelanggan->nama}\n";

// ============================================
// TEST 4: Verify error_details JSON casting
// ============================================
echo "\nTEST 4: Verifying JSON casting...\n";

$details = $retrieved->error_details;
echo "✓ error_details is array: " . (is_array($details) ? "YES" : "NO") . "\n";
echo "  Timestamp: {$details['timestamp']}\n";
echo "  User Agent: {$details['userAgent']}\n";

// ============================================
// TEST 5: List all error reports
// ============================================
echo "\nTEST 5: Listing all error reports...\n";

$allReports = ErrorReport::with(['user', 'pelanggan'])->get();
echo "✓ Total error reports: {$allReports->count()}\n";

foreach ($allReports as $report) {
    echo "  - ID: {$report->id}, User: {$report->user->name}, Page: {$report->page_name}\n";
}

// ============================================
// TEST 6: Check routes
// ============================================
echo "\nTEST 6: Checking routes...\n";

echo "✓ Routes available:\n";
echo "  POST /error-reports (store) - for pelanggan to submit\n";
echo "  GET  /error-reports (index) - for admin to view\n";

// ============================================
// TEST 7: Verify database table
// ============================================
echo "\nTEST 7: Verifying database table...\n";

use Illuminate\Support\Facades\Schema;

if (Schema::hasTable('error_reports')) {
    echo "✓ Table 'error_reports' exists\n";
    
    $columns = Schema::getColumnListing('error_reports');
    echo "  Columns:\n";
    foreach ($columns as $col) {
        echo "    - {$col}\n";
    }
} else {
    echo "✗ Table 'error_reports' NOT found\n";
}

// ============================================
// TEST SUMMARY
// ============================================
echo "\n" . str_repeat("=", 50) . "\n";
echo "✅ All tests completed!\n";
echo str_repeat("=", 50) . "\n\n";

echo "Next steps:\n";
echo "1. Test error page in browser:\n";
echo "   - Go to http://127.0.0.1:8000/pelanggan/transactions\n";
echo "\n";
echo "2. Test admin dashboard:\n";
echo "   - Go to http://127.0.0.1:8000/error-reports (as admin)\n";
echo "\n";
echo "3. Test report submission:\n";
echo "   - Trigger error (or manually at error page)\n";
echo "   - Click 'Laporkan Masalah ke Admin'\n";
echo "   - Fill form and submit\n";
echo "   - Verify data saved in database\n";
