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
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->string('id_pelanggan', 16)->primary()->nullable(false);
            $table->string('nama_pelanggan', 255)->nullable(false);
            $table->string('alamat', 255)->nullable();
            $table->integer('no_kwh')->nullable(false);
            $table->integer('jumlah_meter')->nullable(false);
            $table->string('password', 255)->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};
