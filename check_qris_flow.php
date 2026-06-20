<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$db = $app->make('db');

// Ambil semua pembayaran dengan metode QRIS yang pernah dibuat
$payments = $db->table('pembayaran')
    ->where('metode_pembayaran', 'qris')
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();

echo "=== Last 10 QRIS Payments ===\n";
foreach ($payments as $p) {
    echo "\n--- Payment ID {$p->id} (Transaksi {$p->transaksi_id}) ---\n";
    echo "Amount: {$p->jumlah_bayar}\n";
    echo "Status: {$p->status_pembayaran}\n";
    echo "QR Token: " . ($p->qr_token ? substr($p->qr_token, 0, 10) . '...' : 'NULL') . "\n";
    echo "Created: {$p->created_at}\n";
    echo "Updated: {$p->updated_at}\n";
}

echo "\n\n=== Check pembayaran record structure ===\n";
$columns = $db->select("SHOW COLUMNS FROM pembayaran");
foreach ($columns as $col) {
    echo "{$col->Field} ({$col->Type}) - Default: {$col->Default}\n";
}
