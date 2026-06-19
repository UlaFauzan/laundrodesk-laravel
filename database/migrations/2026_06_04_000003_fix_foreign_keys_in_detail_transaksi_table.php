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
        Schema::table('detail_transaksi', function (Blueprint $table) {
            // Convert to proper foreignId
            $table->dropColumn('transaksi_id', 'layanan_id');
        });

        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->foreignId('transaksi_id')->constrained('transaksi')->onDelete('cascade');
            $table->foreignId('layanan_id')->constrained('layanan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['transaksi_id', 'layanan_id']);
            $table->dropColumn('transaksi_id', 'layanan_id');
        });

        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->unsignedBigInteger('transaksi_id');
            $table->unsignedBigInteger('layanan_id');
        });
    }
};
