<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $controller = new App\Http\Controllers\TransaksiController();
    $response = $controller->create();
    if (is_object($response) && method_exists($response, 'render')) {
        echo $response->render();
    } else {
        print_r($response);
    }
} catch (\Throwable $e) {
    echo 'Exception: ' . get_class($e) . "\n";
    echo 'Message: ' . $e->getMessage() . "\n";
    echo 'File: ' . $e->getFile() . ':' . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString();
}
