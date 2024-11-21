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
        Schema::create('objek_retribusis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('id_jenis_retribusi');
            $table->string('deskripsi');
            $table->double('tarif');
            $table->integer('aktif');
            $table->softDeletes();
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
        Schema::dropIfExists('objek_retribusis');
    }
};
