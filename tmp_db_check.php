<?php

$pdo = new PDO('mysql:host=127.0.0.1;dbname=laundry_db;charset=utf8mb4', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$row = $pdo->query('select count(*) as total from users')->fetch(PDO::FETCH_ASSOC);
echo 'users=' . $row['total'] . PHP_EOL;
$row = $pdo->query('select count(*) as total from roles')->fetch(PDO::FETCH_ASSOC);
echo 'roles=' . $row['total'] . PHP_EOL;

foreach (['admin@mail.com','kasir@mail.com','manager@mail.com','pelanggan@laundry.test'] as $email) {
    $stmt = $pdo->prepare('select id,name,email,role_id from users where email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        echo "FOUND: {$user['email']} role_id={$user['role_id']} id={$user['id']} name={$user['name']}\n";
    } else {
        echo "MISSING: $email\n";
    }
}
