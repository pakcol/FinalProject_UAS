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
        Schema::table('kingdoms', function (Blueprint $table) {
            // Drop existing foreign key constraint
            $table->dropForeign(['user_id']);
            
            // Modify user_id to be nullable
            $table->foreignId('user_id')->nullable()->change()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kingdoms', function (Blueprint $table) {
            // Drop nullable foreign key
            $table->dropForeign(['user_id']);
            
            // Revert to non-nullable
            $table->foreignId('user_id')->nullable(false)->change()->constrained()->onDelete('cascade');
        });
    }
};
