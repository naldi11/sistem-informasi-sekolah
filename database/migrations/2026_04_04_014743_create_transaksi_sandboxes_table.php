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
        Schema::create('transaksi_sandbox', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->decimal('total_nominal', 15, 2);
            $table->string('metode_pembayaran'); // misal: qris, va_bca, va_mandiri
            $table->string('tipe'); // qris atau va
            $table->string('kode_pembayaran'); // alamat URL qr code tau nomor VA
            $table->enum('status', ['pending', 'sukses', 'kadaluarsa', 'gagal'])->default('pending');
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_sandbox');
    }
};
