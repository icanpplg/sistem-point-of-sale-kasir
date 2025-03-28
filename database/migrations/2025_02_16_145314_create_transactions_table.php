<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamp('transaction_date')->nullable(); // tanggal dan waktu transaksi
            $table->string('cashier'); // nama kasir, misalnya "Ibu Nouval"
            $table->integer('total'); // total pembayaran dalam rupiah
            $table->integer('payment')->nullable(); // jumlah pembayaran dari pelanggan
            $table->integer('change')->nullable(); // kembalian
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
