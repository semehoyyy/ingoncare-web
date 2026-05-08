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
        Schema::create('pengingats', function (Blueprint $table) {
            $table->id();

            // Nama hewan peliharaan
            $table->string('nama_hewan');

            // Kategori pengingat (Vaksinasi, Pemeriksaan Ulang, UVB, Grooming, dll)
            $table->string('kategori');

            // Tanggal & Waktu pengingat
            $table->date('tanggal');
            $table->time('waktu');

            // Deskripsi tambahan
            $table->text('deskripsi')->nullable();

            // status: aktif / selesai
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengingats');
    }
};
