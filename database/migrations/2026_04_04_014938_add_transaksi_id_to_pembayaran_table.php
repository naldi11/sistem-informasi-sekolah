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
            $table->foreignId('transaksi_sandbox_id')->nullable()->constrained('transaksi_sandbox')->onDelete('cascade');
            $table->string('file_bukti')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropForeign(['transaksi_sandbox_id']);
            $table->dropColumn('transaksi_sandbox_id');
            $table->string('file_bukti')->nullable(false)->change();
        });
    }
};
