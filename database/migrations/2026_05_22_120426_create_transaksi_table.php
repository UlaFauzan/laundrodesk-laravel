<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pelanggan_id')
                    ->constrained('pelanggan');

            $table->foreignId('layanan_id')
                    ->constrained('layanan');

            $table->decimal('berat_kg', 5, 2);

            $table->integer('total_harga');

            $table->string('status');

            $table->date('tanggal_masuk');
            $table->date('tanggal_selesai')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
