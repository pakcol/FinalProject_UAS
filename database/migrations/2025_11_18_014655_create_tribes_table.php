<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTribesTable extends Migration
{
    public function up()
    {
        Schema::create('tribes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->integer('melee_attack')->default(0);
            $table->integer('range_attack')->default(0);
            $table->integer('magic_attack')->default(0);
            $table->integer('melee_defense')->default(0);
            $table->integer('range_defense')->default(0);
            $table->integer('magic_defense')->default(0);
            $table->string('head_appearance')->nullable();
            $table->string('body_appearance')->nullable();
            $table->string('legs_appearance')->nullable();
            $table->string('hands_appearance')->nullable();
            $table->integer('troop_production_rate')->default(5);
            $table->boolean('is_active')->default(true); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tribes');
    }
}