<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildingsTable extends Migration
{
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // main, barracks, mine, walls
            $table->text('description');
            $table->integer('gold_cost')->default(0);
            $table->integer('level')->default(1);
            $table->integer('gold_production')->default(0);
            $table->integer('troop_production')->default(0);
            $table->integer('defense_bonus')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('buildings');
    }
}