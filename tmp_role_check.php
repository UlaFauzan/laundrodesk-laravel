<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Facade;

require __DIR__ . '/vendor/autoload.php';
putenv('APP_ENV=local');
putenv('DB_CONNECTION=mysql');
putenv('DB_HOST=127.0.0.1');
putenv('DB_PORT=3306');
putenv('DB_DATABASE=laundry_db');
putenv('DB_USERNAME=root');
putenv('DB_PASSWORD=');
putenv('SESSION_DRIVER=array');

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
Facade::setFacadeApplication($app);

$users = [
    'admin@mail.com' => 'Admin',
    'kasir@mail.com' => 'Kasir',
    'manager@mail.com' => 'Manager',
    'pelanggan@laundry.test' => 'Pelanggan',
];

foreach ($users as $email => $label) {
    $user = App\Models\User::where('email', $email)->first();

    if (! $user) {
        echo "$label ($email): missing\n";
        continue;
    }

    $role = strtolower($user->role?->nama_role ?? 'none');
    $redirect = match ($role) {
        'admin' => '/admin',
        'kasir' => '/transaksi',
        'manager' => '/laporan-pendapatan',
        'pelanggan' => '/pelanggan/profile',
        default => 'unknown',
    };

    $authResult = Auth::attempt(['email' => $email, 'password' => 'password']);
    echo "$label ($email): role=$role, redirect=$redirect, auth=" . ($authResult ? 'OK' : 'FAIL') . "\n";
}
