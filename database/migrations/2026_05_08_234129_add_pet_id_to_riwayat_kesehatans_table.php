<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riwayat_kesehatans', function (Blueprint $table) {

            // cek kalau pet_id belum ada
            if (!Schema::hasColumn('riwayat_kesehatans', 'pet_id')) {

                $table->foreignId('pet_id')
                    ->after('user_id')
                    ->constrained('pets')
                    ->onDelete('cascade');
            }

            // cek kalau jenis_hewan belum ada
            if (!Schema::hasColumn('riwayat_kesehatans', 'jenis_hewan')) {

                $table->string('jenis_hewan')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('riwayat_kesehatans', function (Blueprint $table) {

            if (Schema::hasColumn('riwayat_kesehatans', 'pet_id')) {
                $table->dropForeign(['pet_id']);
                $table->dropColumn('pet_id');
            }

            if (Schema::hasColumn('riwayat_kesehatans', 'jenis_hewan')) {
                $table->dropColumn('jenis_hewan');
            }
        });
    }
};