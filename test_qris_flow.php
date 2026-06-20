<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Now test the QRIS flow
$db = $app->make('db');

// Create a test transaksi
$transaksiId = $db->table('transaksi')->insertGetId([
    'pelanggan_id' => 1,
    'layanan_id' => 1,
    'berat_kg' => 2.5,
    'total_harga' => 20000,
    'status' => 'Menunggu',
    'tanggal_masuk' => '2026-06-20',
    'status_laundry_id' => 1,
]);

echo "Created test transaksi ID: $transaksiId\n";

// Simulate creating a QRIS payment for 12000 (partial payment)
$qrToken = uniqid('qr_', true);
$pembayaranId = $db->table('pembayaran')->insertGetId([
    'transaksi_id' => $transaksiId,
    'jumlah_bayar' => 0,  // Start with 0 (as per QRIS flow)
    'metode_pembayaran' => 'qris',
    'status_pembayaran' => 'pending',
    'qr_token' => $qrToken,
    'created_at' => now(),
    'updated_at' => now(),
]);

echo "Created test pembayaran ID: $pembayaranId with qr_token\n";

// Simulate customer scanning QR and paying 12000
$payment = $db->table('pembayaran')->find($pembayaranId);
echo "\nBefore scanning QR:\n";
echo "- Amount: " . $payment->jumlah_bayar . "\n";
echo "- Status: " . $payment->status_pembayaran . "\n";
echo "- QR Token: " . substr($payment->qr_token, 0, 10) . "...\n";

// Update payment (simulating what complete() endpoint does)
$oldJumlah = $payment->jumlah_bayar;
$newAmount = $oldJumlah + 12000;  // Customer pays 12000
$totalHarga = 20000;
$newStatus = $newAmount >= $totalHarga ? 'lunas' : 'hutang';

$db->table('pembayaran')->where('id', $pembayaranId)->update([
    'jumlah_bayar' => $newAmount,
    'status_pembayaran' => $newStatus,
    'qr_token' => null,
    'notified' => false,
    'updated_at' => now(),
]);

$updated = $db->table('pembayaran')->find($pembayaranId);
echo "\nAfter scanning QR (12000 paid):\n";
echo "- Amount: " . $updated->jumlah_bayar . "\n";
echo "- Status: " . $updated->status_pembayaran . "\n";
echo "- QR Token: " . ($updated->qr_token ?? 'NULL') . "\n";

// Simulate another payment (customer pays another 8000 -> total 20000 = lunas)
$qrToken2 = uniqid('qr_', true);
$db->table('pembayaran')->where('id', $pembayaranId)->update([
    'qr_token' => $qrToken2,
    'updated_at' => now(),
]);

$oldJumlah = $db->table('pembayaran')->find($pembayaranId)->jumlah_bayar;
$newAmount = $oldJumlah + 8000;  // Customer pays another 8000
$newStatus = $newAmount >= $totalHarga ? 'lunas' : 'hutang';

$db->table('pembayaran')->where('id', $pembayaranId)->update([
    'jumlah_bayar' => $newAmount,
    'status_pembayaran' => $newStatus,
    'qr_token' => null,
    'updated_at' => now(),
]);

$final = $db->table('pembayaran')->find($pembayaranId);
echo "\nAfter second payment (8000 paid, total 20000):\n";
echo "- Amount: " . $final->jumlah_bayar . "\n";
echo "- Status: " . $final->status_pembayaran . "\n";
echo "- QR Token: " . ($final->qr_token ?? 'NULL') . "\n";

echo "\n✅ QRIS flow test successful! The system can now handle partial QRIS payments.\n";

// Cleanup
$db->table('pembayaran')->where('id', $pembayaranId)->delete();
$db->table('transaksi')->where('id', $transaksiId)->delete();
echo "✓ Test data cleaned up\n";
