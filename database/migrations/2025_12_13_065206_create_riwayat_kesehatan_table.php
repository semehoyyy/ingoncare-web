<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_kesehatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_hewan');
            $table->string('spesies')->nullable();
            $table->string('jenis_hewan');
            $table->integer('umur')->default(0);
            $table->integer('umur_bulan')->default(0);
            $table->enum('jenis_kelamin', ['Jantan', 'Betina']);
            $table->date('tanggal_pemeriksaan');
            $table->string('diagnosis');
            $table->text('tindakan');
            $table->string('dokter');
            $table->text('catatan')->nullable();
            $table->date('jadwal_berikutnya')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_kesehatans');
    }
};