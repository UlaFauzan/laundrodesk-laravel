<?php
require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = \App\Models\User::with(['role', 'pelanggan'])->get();
foreach ($users as $u) {
    echo "User: " . $u->name . " (role: " . ($u->role?->nama_role ?? 'N/A') . ", pelanggan: " . ($u->pelanggan?->nama ?? 'N/A') . ")" . PHP_EOL;
}
