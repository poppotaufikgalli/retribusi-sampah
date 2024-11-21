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
        Schema::create('aktifasi_wrs', function (Blueprint $table) {
            $table->id();
            $table->string('npwrd', 18);
            $table->integer('aktif')->default(0);
            $table->string('no_sk');
            $table->date('tgl_sk');
            $table->date('tmt_sk');
            $table->string('catatan');
            $table->integer('id_user');
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
        Schema::dropIfExists('aktifasi_wrs');
    }
};
