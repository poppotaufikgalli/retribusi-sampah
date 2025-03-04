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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->integer('jns');
            $table->integer('tipe');
            $table->integer('id_wr');
            $table->string('npwrd')->nullable();
            $table->string('no_karcis')->nullable();
            $table->string('id_karcis')->nullable();
            $table->integer('thn');
            $table->integer('bln');
            $table->integer('tgl')->default(1);
            $table->double('jml')->default(0);
            $table->double('denda')->default(0);
            $table->double('total');
            $table->double('pembayaran_ke')->default(1);
            $table->integer('id_user');
            $table->date('tgl_bayar');
            $table->integer('verif')->default(0);
            $table->integer('verif_id');
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
        Schema::dropIfExists('pembayarans');
    }
};
