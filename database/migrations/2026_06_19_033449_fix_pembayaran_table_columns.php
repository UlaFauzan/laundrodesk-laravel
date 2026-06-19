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
        Schema::table('pembayaran', function (Blueprint $table) {
            // Add qr_token if not exists
            if (!Schema::hasColumn('pembayaran', 'qr_token')) {
                $table->string('qr_token')->nullable()->unique();
            }
            // Add notified if not exists
            if (!Schema::hasColumn('pembayaran', 'notified')) {
                $table->boolean('notified')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            if (Schema::hasColumn('pembayaran', 'qr_token')) {
                $table->dropColumn('qr_token');
            }
            if (Schema::hasColumn('pembayaran', 'notified')) {
                $table->dropColumn('notified');
            }
        });
    }
};
