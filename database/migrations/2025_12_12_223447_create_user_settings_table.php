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
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Notification Settings
            $table->boolean('push_enabled')->default(true);
            $table->boolean('notif_likes')->default(true);
            $table->boolean('notif_comments')->default(true);
            $table->boolean('notif_reminders')->default(true);
            $table->boolean('email_weekly')->default(false);
            $table->boolean('email_tips')->default(false);
            
            // Privacy Settings
            $table->boolean('profile_public')->default(true);
            $table->boolean('show_email')->default(false);
            $table->boolean('show_online_status')->default(true);
            
            // Appearance Settings
            $table->enum('theme', ['light', 'dark', 'auto'])->default('light');
            $table->boolean('animations_enabled')->default(true);
            $table->boolean('compact_mode')->default(false);
            
            // Language Settings
            $table->string('language', 10)->default('id');
            
            $table->timestamps();
            
            // Unique constraint: one setting per user
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};