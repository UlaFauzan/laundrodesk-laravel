<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserRoleDirectLoginTest extends TestCase
{
    public function test_admin_can_login_and_redirect_to_admin_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@mail.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticated();
    }

    public function test_kasir_can_login_and_redirect_to_transaksi(): void
    {
        $response = $this->post('/login', [
            'email' => 'kasir@mail.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/transaksi');
        $this->assertAuthenticated();
    }

    public function test_manager_can_login_and_redirect_to_laporan_pendapatan(): void
    {
        $response = $this->post('/login', [
            'email' => 'manager@mail.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/laporan-pendapatan');
        $this->assertAuthenticated();
    }

    public function test_pelanggan_can_login_and_redirect_to_pelanggan_profile(): void
    {
        $response = $this->post('/login', [
            'email' => 'pelanggan@laundry.test',
            'password' => 'password',
        ]);

        $response->assertRedirect('/pelanggan/profile');
        $this->assertAuthenticated();
    }
}
