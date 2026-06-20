<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$db = $app['db'];
$result = $db->select('SHOW COLUMNS FROM transaksi');
foreach($result as $col) {
    if($col->Field === 'status_pembayaran') {
        echo "✓ FOUND: {$col->Field} ({$col->Type})\n";
        exit(0);
    }
}
echo "✗ Column status_pembayaran NOT found\n";
exit(1);
