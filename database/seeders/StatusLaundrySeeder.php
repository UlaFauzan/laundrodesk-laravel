<?php

namespace Database\Seeders;

use App\Models\StatusLaundry;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusLaundrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatusLaundry::create(['nama_status' => 'Menunggu']);
        StatusLaundry::create(['nama_status' => 'Sedang Diproses']);
        StatusLaundry::create(['nama_status' => 'Selesai']);
        StatusLaundry::create(['nama_status' => 'Diambil']);
    }
}
