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
        Schema::create('pengembalians', function (Blueprint $table) {
            $table->id();
            $table->integer('id_karcis');
            $table->string('no_karcis_awal');
            $table->string('no_karcis_akhir');
            $table->string('no_karcis_pengembalian');
            $table->string('catatan')->nullable();
            $table->date('tgl_pengembalian')->nullable();
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
        Schema::dropIfExists('pengembalians');
    }
};
