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
        Schema::table('comments', function (Blueprint $table) {
            // Cek dulu kolom 'title'
            if (!Schema::hasColumn('comments', 'title')) {
                $table->string('title')->nullable()->after('content'); // Judul post
            }

            // Cek dulu kolom 'hashtags'
            if (!Schema::hasColumn('comments', 'hashtags')) {
                $table->text('hashtags')->nullable()->after('title'); // Hashtags (comma separated)
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // Hapus kolom hanya kalau ada
            if (Schema::hasColumn('comments', 'title')) {
                $table->dropColumn('title');
            }
            if (Schema::hasColumn('comments', 'hashtags')) {
                $table->dropColumn('hashtags');
            }
        });
    }
};
