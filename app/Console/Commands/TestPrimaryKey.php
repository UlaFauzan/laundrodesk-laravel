<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pelanggan;

class TestPrimaryKey extends Command
{
    protected $signature = 'test:primarykey';

    public function handle()
    {
        $p = new Pelanggan();
        $this->line('Pelanggan model primary key: ' . $p->getKeyName());
        
        // Try actual query
        try {
            $pelanggan = Pelanggan::first();
            $this->line('First pelanggan ID: ' . $pelanggan?->id . ' (using id attribute)');
            $this->line('Pelanggan key: ' . ($pelanggan?->getKey() ?? 'null'));
        } catch (\Exception $e) {
            $this->error('Query error: ' . $e->getMessage());
        }
    }
}
