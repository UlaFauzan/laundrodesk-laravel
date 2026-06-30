<?php

namespace Tests\Feature;

use App\Http\Controllers\ErrorReportController;
use App\Models\ErrorReport;
use App\Models\Notifikasi;
use App\Models\Pelanggan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrorReportNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_resolving_report_creates_notification_for_customer(): void
    {
        $role = Role::create(['nama_role' => 'pelanggan']);
        $pelanggan = Pelanggan::create([
            'nama' => 'Budi',
            'telepon' => '081234567890',
            'alamat' => 'Bandung',
        ]);

        $user = User::create([
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'password' => 'password',
            'role_id' => $role->id,
            'pelanggan_id' => $pelanggan->id,
        ]);

        $report = ErrorReport::create([
            'user_id' => $user->id,
            'pelanggan_id' => $pelanggan->id,
            'page_name' => 'Halaman Profil',
            'description' => 'Ada masalah saat membuka profil',
        ]);

        $response = app(ErrorReportController::class)->resolve($report->id);

        $this->assertTrue($response->getData(true)['success']);
        $this->assertDatabaseHas('notifikasi', [
            'pelanggan_id' => $pelanggan->id,
            'status_baca' => 'Belum Dibaca',
        ]);

        $notification = Notifikasi::latest()->first();
        $this->assertSame(
            'Admin telah mengonfirmasi laporan masalah Anda untuk halaman "Halaman Profil".',
            $notification->pesan
        );
    }
}
