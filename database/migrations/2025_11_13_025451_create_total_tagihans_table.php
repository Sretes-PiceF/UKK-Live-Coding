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
        Schema::create('total_tagihan', function (Blueprint $table) {
            $table->string('id_total_tagihan', 16)->primary()->nullable(false);
            $table->string('id_tagihan', 16)->nullable(false);
            $table->string('id_pelanggan', 16)->nullable(false);
            $table->date('tanggal_bayar')->nullable();
            $table->integer('biaya_admin')->nullable(false);
            $table->integer('total_bayar')->nullable(false);
            $table->enum('status_pembayaran', ['Dibayar', 'Belum bayar'])->default('Belum bayar')->nullable(false);
            $table->timestamps();

            $table->foreign('id_tagihan')->references('id_tagihan')->on('tagihan')->onDelete('cascade');
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('total_tagihans');
    }
};
