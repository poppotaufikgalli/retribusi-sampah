<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrasi_karcis', function (Blueprint $table) {
            $table->id();
            $table->string('no_serah_terima')->nullable();
            $table->string('deskripsi')->nullable();
            $table->integer('id_user_koordinator');
            $table->integer('id_user_juru_pungut');
            $table->date('tgl_penyerahan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registrasi_karcis');
    }
};
