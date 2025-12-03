<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKingdomsTable extends Migration
{
    public function up()
    {
        Schema::create('kingdoms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tribe_id')->constrained();
            $table->string('name');
            $table->integer('gold')->default(100);
            $table->integer('main_building_level')->default(1);
            $table->integer('barracks_count')->default(0);
            $table->integer('mines_count')->default(0);
            $table->integer('walls_count')->default(0);
            $table->integer('total_troops')->default(0);
            $table->integer('total_attack_power')->default(0);
            $table->integer('total_defense_power')->default(0);
            $table->timestamp('last_resource_update')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kingdoms');
    }
}