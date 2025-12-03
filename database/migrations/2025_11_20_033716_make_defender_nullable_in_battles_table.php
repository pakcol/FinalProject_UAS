<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up()
    {
        Schema::table('battles', function (Blueprint $table) {
            // 1. Hapus foreign key lama
            $table->dropForeign(['defender_id']);

            // 2. Jadikan kolom defender_id nullable
            $table->unsignedBigInteger('defender_id')->nullable()->change();

            // 3. Tambah foreign key baru (tetap ke kingdoms)
            $table->foreign('defender_id')
                  ->references('id')
                  ->on('kingdoms')
                  ->onDelete('set null'); // kalau kingdomnya dihapus, kolom menjadi null
        });
    }

    public function down()
    {
        Schema::table('battles', function (Blueprint $table) {
            // 1. Hapus foreign key baru
            $table->dropForeign(['defender_id']);

            // 2. Kembalikan defender_id menjadi NOT NULL
            $table->unsignedBigInteger('defender_id')->nullable(false)->change();

            // 3. Tambahkan FK seperti awal (wajib ada defender)
            $table->foreign('defender_id')
                  ->references('id')
                  ->on('kingdoms');
        });
    }
};
