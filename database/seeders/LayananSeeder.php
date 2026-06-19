<?php

namespace Database\Seeders;

use App\Models\Layanan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Layanan::insert([
    [
        'nama_layanan' => 'Cuci Kering',
        'harga_per_kg' => 5000,
        'deskripsi' => 'Cuci dan kering'
    ],
    [
        'nama_layanan' => 'Cuci Setrika',
        'harga_per_kg' => 7000,
        'deskripsi' => 'Cuci, kering, setrika'
    ],
    [
        'nama_layanan' => 'Express',
        'harga_per_kg' => 10000,
        'deskripsi' => 'Selesai dalam 1 hari'
    ]
]);
    }
}
