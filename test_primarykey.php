<?php
// Test what primary key Eloquent thinks Pelanggan has
require 'bootstrap/app.php';

$app = \Illuminate\Foundation\Application::getInstance();
$pelanggan_model = new \App\Models\Pelanggan();
echo "Pelanggan primaryKey: " . $pelanggan_model->getKeyName() . PHP_EOL;
