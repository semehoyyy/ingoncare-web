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
        Schema::create('hewans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pengguna'); // relasi ke users
            $table->string('nama_hewan');
            $table->string('jenis');
            $table->string('ras');
            $table->integer('umur');
            $table->string('foto')->nullable(); // nullable karena bisa kosong
            $table->timestamps();

            $table->foreign('id_pengguna')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hewans');
    }
};
