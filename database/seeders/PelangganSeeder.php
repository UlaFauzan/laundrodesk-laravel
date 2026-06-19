<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pelanggan')->insert([
            [
                'nama' => 'Budi Santoso',
                'telepon' => '081234567890',
                'alamat' => 'Bandung',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Siti Rahma',
                'telepon' => '082233445566',
                'alamat' => 'Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}