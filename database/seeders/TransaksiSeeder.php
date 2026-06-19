<?php

namespace Database\Seeders;

use App\Models\Transaksi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transaksi::create([
            'pelanggan_id' => 1,
            'layanan_id' => 1,
            'berat_kg' => 5,
            'total_harga' => 25000,
            'status' => 'pending',
            'status_laundry_id' => 1,
            'tanggal_masuk' => now(),
            'tanggal_selesai' => null
        ]);

        Transaksi::create([
            'pelanggan_id' => 2,
            'layanan_id' => 2,
            'berat_kg' => 3.5,
            'total_harga' => 24500,
            'status' => 'pending',
            'status_laundry_id' => 1,
            'tanggal_masuk' => now(),
            'tanggal_selesai' => null
        ]);
    }
}
