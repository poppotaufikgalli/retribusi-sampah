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
        Schema::create('pendaftars', function (Blueprint $table) {
            $table->id();
            $table->integer('id_lomba');
            $table->integer('id_peserta');
            $table->string('no_peserta')->nullable();
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->string('pic')->nullable();
            $table->string('telp')->nullable();
            $table->string('ketua')->nullable();
            $table->string('telp_ketua')->nullable();
            $table->integer('aktif');
            $table->integer('total')->nullable();
            $table->integer('diskualifikasi')->default(0);
            $table->integer('verif_id')->nullable();
            $table->datetime('verif_date')->nullable();
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
        Schema::dropIfExists('pendaftars');
    }
};
