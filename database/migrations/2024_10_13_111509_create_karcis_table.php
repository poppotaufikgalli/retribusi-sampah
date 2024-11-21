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
        Schema::create('karcis', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_penyerahan');
            $table->string('tahun');
            $table->double('harga');
            $table->integer('id_registrasi_karcis')->nullable();
            $table->string('no_karcis_awal');
            $table->string('no_karcis_akhir');
            $table->integer('id_wilayah')->nullable();
            $table->integer('id_user_koordinator')->nullable();
            $table->integer('id_user_juru_pungut')->nullable();
            $table->integer('stts')->default(1);
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
        Schema::dropIfExists('karcis');
    }
};
