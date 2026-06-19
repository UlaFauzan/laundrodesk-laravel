<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$db = Illuminate\Support\Facades\DB::connection();
$columns = $db->select('SHOW FULL COLUMNS FROM pembayaran');

echo "TABLE: pembayaran\n";
foreach ($columns as $col) {
    echo '  ' . $col->Field . ' ' . $col->Type 
        . ($col->Null === 'NO' ? ' NOT NULL' : ' NULL') 
        . ($col->Key ? ' ' . $col->Key : '') 
        . ($col->Extra ? ' ' . $col->Extra : '') . "\n";
}
