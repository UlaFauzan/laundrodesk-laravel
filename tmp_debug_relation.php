<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Events\QueryExecuted;

$m = new Pelanggan();
echo "Pelanggan getKeyName: " . $m->getKeyName() . PHP_EOL;

echo "Pelanggan table: " . $m->getTable() . PHP_EOL;

echo "User model file: " . (new ReflectionClass(User::class))->getFileName() . PHP_EOL;

echo "Pelanggan model file: " . (new ReflectionClass(Pelanggan::class))->getFileName() . PHP_EOL;

$u = User::first();
if ($u) {
    $relation = $u->pelanggan();
    echo "Relation class: " . get_class($relation) . PHP_EOL;
    echo "Foreign key: " . $relation->getForeignKeyName() . PHP_EOL;
    echo "Owner key: " . $relation->getOwnerKeyName() . PHP_EOL;
    echo "Related model key: " . $relation->getRelated()->getKeyName() . PHP_EOL;
    echo "Relation query SQL: " . $relation->getQuery()->toSql() . PHP_EOL;
} else {
    echo "No User records found\n";
}

DB::listen(function (QueryExecuted $query) {
    echo "[SQL] " . $query->sql . "\n";
    echo "[BINDINGS] " . implode(', ', array_map('strval', $query->bindings)) . "\n";
});

try {
    echo "Fetching users with pelanggan...\n";
    $users = User::with('pelanggan')->get();
    echo "Fetched users count: " . $users->count() . "\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
