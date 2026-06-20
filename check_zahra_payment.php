<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get DB instance from container
$db = $app->make('db');

// Query payment records
$payments = $db->table('pembayaran')
    ->where('transaksi_id', 5)
    ->get();

echo "=== Payment Records for Transaksi 5 (Zahra) ===\n";
if (count($payments) === 0) {
    echo "No payment records found.\n";
} else {
    foreach ($payments as $p) {
        echo json_encode($p, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    }
}

// Query transaksi record
$transaksi = $db->table('transaksi')->find(5);
echo "\n=== Transaksi 5 (Zahra) ===\n";
if ($transaksi) {
    echo json_encode($transaksi, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
} else {
    echo "No transaksi found.\n";
}
