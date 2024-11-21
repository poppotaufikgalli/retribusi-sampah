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
        Schema::create('kat_pesertas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_lomba');
            $table->string('judul');
            $table->string('ket')->nullable();
            $table->integer('ref_kecepatan')->default(6);
            $table->integer('no_peserta_mulai')->default(31);
            $table->string('no_peserta_prefix')->nullable();
            $table->integer('aktif');
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
        Schema::dropIfExists('kat_pesertas');
    }
};
