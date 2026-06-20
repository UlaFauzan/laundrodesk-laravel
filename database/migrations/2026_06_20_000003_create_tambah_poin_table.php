<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tambah_poin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggan');
            $table->foreignId('transaksi_id')->nullable()->constrained('transaksi');
            $table->integer('jumlah_poin');
            $table->string('alasan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tambah_poin');
    }
};
