<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::find(2);
if (! $user) {
    echo "User id=2 not found\n";
    exit(1);
}

$user->password = Hash::make('password');
$user->save();

echo "Password for user id=2 updated.\n";
