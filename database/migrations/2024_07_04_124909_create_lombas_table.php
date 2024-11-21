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
        Schema::create('lombas', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->string('judul');
            $table->integer('jml_pos');
            $table->string('ket')->nullable();
            $table->integer('aktif');
            $table->integer('verif_id');
            $table->datetime('waktu_start');
            $table->datetime('waktu_finnish');
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
        Schema::dropIfExists('lombas');
    }
};
