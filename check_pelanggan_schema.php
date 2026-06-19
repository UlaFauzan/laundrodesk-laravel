<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=laundry_db', 'root', '');
$cols = $pdo->query('DESCRIBE pelanggan')->fetchAll(PDO::FETCH_ASSOC);
foreach ($cols as $col) {
    echo $col['Field'] . ' - ' . $col['Type'] . ' - Key: ' . $col['Key'] . PHP_EOL;
}
