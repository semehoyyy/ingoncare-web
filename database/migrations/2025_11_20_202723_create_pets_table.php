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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();

            // relasi ke tabel users
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // basic data
            $table->string('name');
            $table->string('species');
            $table->string('breed')->nullable();

            // tambahan lengkap sesuai form
            $table->string('gender')->nullable();          // jantan/betina
            $table->date('birth_date')->nullable();        // tanggal lahir
            $table->float('weight')->nullable();           // berat
            $table->text('special_marks')->nullable();     // tanda khusus
            $table->boolean('is_steril')->default(0);      // sudah steril?
            $table->string('allergies')->nullable();       // alergi
            $table->text('health_notes')->nullable();      // catatan kesehatan

            // foto hewan
            $table->string('photo')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
