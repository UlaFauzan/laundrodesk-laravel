<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@mail.com',
            'password' => bcrypt('password'),
            'role_id' => 1, // admin
        ]);

        // Kasir user
        User::create([
            'name' => 'Kasir User',
            'email' => 'kasir@mail.com',
            'password' => bcrypt('password'),
            'role_id' => 2, // kasir
        ]);

        // Manager user
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@mail.com',
            'password' => bcrypt('password'),
            'role_id' => 3, // manager
        ]);

        // Pelanggan user
        $pelanggan = Pelanggan::create([
            'nama' => 'Pelanggan User',
            'telepon' => '081298765432',
            'alamat' => 'Surabaya',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        User::create([
            'name' => 'Pelanggan User',
            'email' => 'pelanggan@laundry.test',
            'password' => bcrypt('password'),
            'role_id' => 4, // pelanggan
            'pelanggan_id' => $pelanggan->id,
        ]);
    }
}
