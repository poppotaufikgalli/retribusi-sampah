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
            $table->string('npwrd');
            $table->string('no_karcis')->nullable();
            $table->string('id_karcis')->nullable();
            $table->integer('thn');
            $table->integer('bln');
            $table->double('jml')->default(0);
            $table->double('denda')->default(0);
            $table->double('total');
            $table->integer('id_user');
            $table->timestamps('tgl_bayar');
            $table->integer('verif')->default(0);
            $table->integer('verif_id');
            $table->string('filename')->nullable();
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
