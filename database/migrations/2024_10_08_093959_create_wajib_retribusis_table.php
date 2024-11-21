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
        Schema::create('wajib_retribusis', function (Blueprint $table) {
            $table->string('npwrd', 18)->primary();
            $table->string('nama');
            $table->string('id_pemilik');
            $table->integer('id_kecamatan');
            $table->integer('id_kelurahan');
            $table->string('alamat');
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->integer('id_objek_retribusi');
            $table->integer('id_wilayah');
            $table->integer('aktif')->default(0);
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
        Schema::dropIfExists('wajib_retribusis');
    }
};
