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
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_penyerahan');
            $table->string('npwrd');
            $table->string('no_skrd');
            $table->date('tgl_skrd');
            $table->integer('bln');
            $table->integer('thn');
            $table->double('jml')->default(0);
            $table->integer('id_user');
            $table->integer('stts_bayar')->default(0);
            $table->string('filename_skrd')->nullable();
            $table->integer('id_registrasi_karcis')->nullable();
            $table->integer('id_user_koordinator')->nullable();
            $table->integer('id_user_juru_pungut')->nullable();
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
        Schema::dropIfExists('tagihans');
    }
};
