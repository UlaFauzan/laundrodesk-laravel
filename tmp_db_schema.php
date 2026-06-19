<?php

$pdo = new PDO('mysql:host=127.0.0.1;dbname=laundry_db;charset=utf8mb4', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$tables = $pdo->query('show tables')->fetchAll(PDO::FETCH_COLUMN);
echo 'tables: ' . implode(', ', $tables) . PHP_EOL;

echo 'roles structure:' . PHP_EOL;
foreach ($pdo->query('show create table roles') as $row) {
    echo $row['Create Table'] . PHP_EOL;
}

echo 'users structure:' . PHP_EOL;
foreach ($pdo->query('show create table users') as $row) {
    echo $row['Create Table'] . PHP_EOL;
}

echo 'pembayaran structure:' . PHP_EOL;
foreach ($pdo->query('show create table pembayaran') as $row) {
    echo $row['Create Table'] . PHP_EOL;
}
