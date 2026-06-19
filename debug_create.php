<?php
require 'vendor/autoload.php';

try {
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
    $request = \Illuminate\Http\Request::capture();
    $request->setUserResolver(function() {
        return \App\Models\User::find(2);
    });
    
    $response = $kernel->handle(
        $request = \Illuminate\Http\Request::capture()
    );
    
    echo "Request URI: " . $request->getUri() . "\n";
    
    if ($response instanceof \Illuminate\Http\Response) {
        if (method_exists($response, 'getOriginalContent')) {
            echo "Response type: " . gettype($response->getOriginalContent()) . "\n";
        }
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
