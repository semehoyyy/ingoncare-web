<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('riwayat_kesehatans', function (Blueprint $table) {
        $table->foreignId('pet_id')
              ->after('user_id')
              ->constrained('pets')
              ->onDelete('cascade');

        $table->string('jenis_hewan')->nullable();
    });
}

public function down(): void
{
    Schema::table('riwayat_kesehatans', function (Blueprint $table) {
        $table->dropForeign(['pet_id']);
        $table->dropColumn('pet_id');
        $table->dropColumn('jenis_hewan'); // 🔥 HARUS DROP
    });
}
};