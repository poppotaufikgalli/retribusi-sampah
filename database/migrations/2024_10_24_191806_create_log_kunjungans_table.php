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
        Schema::create('log_kunjungans', function (Blueprint $table) {
            $table->id();
            $table->string('npwrd');
            $table->string('jns');
            $table->string('keterangan')->nullable();
            $table->string('tgl_kunjungan');
            $table->integer('bln')->nullable();
            $table->integer('thn')->nullable();
            $table->string('no_tagihan')->nullable();
            $table->integer('id_user_juru_pungut');
            $table->string('gbr')->nullable();
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
        Schema::dropIfExists('log_kunjungans');
    }
};
