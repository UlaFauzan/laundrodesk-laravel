<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=laundry_db', 'root', '');

// Check users with pelanggan_id
echo "=== Users ===\n";
$users = $pdo->query('SELECT id, name, email, role_id, pelanggan_id FROM users LIMIT 5')->fetchAll(PDO::FETCH_ASSOC);
foreach ($users as $u) {
    echo json_encode($u) . "\n";
}

echo "\n=== Pelanggan ===\n";
$pelanggan = $pdo->query('SELECT id, nama, telepon FROM pelanggan LIMIT 5')->fetchAll(PDO::FETCH_ASSOC);
foreach ($pelanggan as $p) {
    echo json_encode($p) . "\n";
}
