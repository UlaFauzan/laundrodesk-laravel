<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $pdo = $app['db']->connection()->getPdo();
    echo 'OK '.get_class($pdo)."\n";
    echo 'DB name: ' . $app['db']->connection()->getDatabaseName() . "\n";
} catch (\Throwable $e) {
    echo 'EXCEPTION: ' . get_class($e) . "\n";
    echo 'Message: ' . $e->getMessage() . "\n";
}
