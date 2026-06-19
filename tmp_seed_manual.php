<?php

$pdo = new PDO('mysql:host=127.0.0.1;dbname=laundry_db;charset=utf8mb4', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$pdo->exec('SET NAMES utf8mb4');

$roles = ['admin', 'kasir', 'manager', 'pelanggan'];
$now = date('Y-m-d H:i:s');

foreach ($roles as $role) {
    $stmt = $pdo->prepare('select id from roles where nama_role = ?');
    $stmt->execute([$role]);
    if ($stmt->fetchColumn() === false) {
        $insert = $pdo->prepare('insert into roles (nama_role, created_at, updated_at) values (?, ?, ?)');
        $insert->execute([$role, $now, $now]);
        echo "Inserted role: $role\n";
    } else {
        echo "Role exists: $role\n";
    }
}

$roleIds = [];
$stmt = $pdo->query('select id, nama_role from roles');
foreach ($stmt as $row) {
    $roleIds[$row['nama_role']] = $row['id'];
}

$pelangganTelepon = '081298765432';
$stmt = $pdo->prepare('select id from pelanggan where telepon = ?');
$stmt->execute([$pelangganTelepon]);
$pelangganId = $stmt->fetchColumn();
if ($pelangganId === false) {
    $insert = $pdo->prepare('insert into pelanggan (nama, telepon, alamat, created_at, updated_at) values (?, ?, ?, ?, ?)');
    $insert->execute(['Pelanggan User', $pelangganTelepon, 'Surabaya', $now, $now]);
    $pelangganId = $pdo->lastInsertId();
    echo "Inserted pelanggan id: $pelangganId\n";
} else {
    echo "Pelanggan exists id: $pelangganId\n";
}

$users = [
    ['name' => 'Admin User', 'email' => 'admin@mail.com', 'role' => 'admin', 'pelanggan_id' => null],
    ['name' => 'Kasir User', 'email' => 'kasir@mail.com', 'role' => 'kasir', 'pelanggan_id' => null],
    ['name' => 'Manager User', 'email' => 'manager@mail.com', 'role' => 'manager', 'pelanggan_id' => null],
    ['name' => 'Pelanggan User', 'email' => 'pelanggan@laundry.test', 'role' => 'pelanggan', 'pelanggan_id' => $pelangganId],
];

foreach ($users as $user) {
    $stmt = $pdo->prepare('select id from users where email = ?');
    $stmt->execute([$user['email']]);
    if ($stmt->fetchColumn() === false) {
        $passwordHash = password_hash('password', PASSWORD_BCRYPT);
        $insert = $pdo->prepare('insert into users (name, email, password, role_id, pelanggan_id, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)');
        $insert->execute([
            $user['name'],
            $user['email'],
            $passwordHash,
            $roleIds[$user['role']],
            $user['pelanggan_id'],
            $now,
            $now,
        ]);
        echo "Inserted user: {$user['email']}\n";
    } else {
        echo "User exists: {$user['email']}\n";
    }
}

echo "Seed complete.\n";
