<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBattlesTable extends Migration
{
    public function up()
    {
        Schema::create('battles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attacker_id')->constrained('kingdoms');
            $table->foreignId('defender_id')->nullable()->constrained('kingdoms');
            $table->integer('attacker_troops');
            $table->integer('defender_troops');
            $table->integer('attacker_power');
            $table->integer('defender_power');
            $table->integer('gold_stolen')->default(0);
            $table->string('result'); // win, lose, draw
            $table->text('battle_log');
            $table->timestamp('battle_time')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('battles');
    }
}