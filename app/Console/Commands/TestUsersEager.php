<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pelanggan;
use App\Models\User;

class TestUsersEager extends Command
{
    protected $signature = 'test:users-eager';

    public function handle()
    {
        $p = new Pelanggan();
        $this->line('Pelanggan model primary key: ' . $p->getKeyName());

        try {
            $this->line('Attempting User::with(["pelanggan"])...');
            $users = User::with(['pelanggan'])->get();
            $this->line('Fetched ' . $users->count() . ' users');
        } catch (\Throwable $e) {
            $this->error('Throwable: ' . $e->getMessage());
            $this->line($e->getTraceAsString());
        }
    }
}
