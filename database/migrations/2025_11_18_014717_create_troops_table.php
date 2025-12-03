<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTroopsTable extends Migration
{
    public function up()
    {
        Schema::create('troops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kingdom_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->integer('melee_attack')->default(0);
            $table->integer('range_attack')->default(0);
            $table->integer('magic_attack')->default(0);
            $table->integer('melee_defense')->default(0);
            $table->integer('range_defense')->default(0);
            $table->integer('magic_defense')->default(0);
            $table->timestamp('last_production_update')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('troops');
    }
}