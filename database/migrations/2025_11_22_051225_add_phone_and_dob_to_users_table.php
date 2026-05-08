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
        Schema::table('users', function (Blueprint $table) {
            // Cek dulu kolom 'phone'
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->unique()->after('email');
            }

            // Cek dulu kolom 'dob'
            if (!Schema::hasColumn('users', 'dob')) {
                $table->date('dob')->nullable()->after('phone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom hanya kalau ada
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('users', 'dob')) {
                $table->dropColumn('dob');
            }
        });
    }
};
