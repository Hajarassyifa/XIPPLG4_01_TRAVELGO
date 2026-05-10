<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('destinasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_destinasi');
            $table->string('lokasi');
            $table->text('deskripsi')->nullable();
            $table->integer('harga_tiket');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('destinasi');
    }
};