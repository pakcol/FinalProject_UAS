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
        Schema::create('user_character_customizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->foreignId('head_part_id')->nullable()->constrained('tribe_appearance_parts')->onDelete('set null');
            $table->foreignId('body_part_id')->nullable()->constrained('tribe_appearance_parts')->onDelete('set null');
            $table->foreignId('legs_part_id')->nullable()->constrained('tribe_appearance_parts')->onDelete('set null');
            $table->foreignId('arms_part_id')->nullable()->constrained('tribe_appearance_parts')->onDelete('set null');
            $table->timestamps();

            // Index for faster lookups
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_character_customizations');
    }
};
