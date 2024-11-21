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
        Schema::create('penilaians', function (Blueprint $table) {
            $table->integer('id_pendaftar')->unsigned();
            $table->integer('id_juri')->unsigned();
            $table->integer('id_nilai')->unsigned(); //1. waktu_tempuh, 2. nilai_waktu, 3. Keutuhan Barisan 4. Kerapian, 5. Semangat
            $table->integer('nilai')->default(0);
            $table->integer('uid');
            $table->timestamps();

            $table->primary(['id_pendaftar', 'id_juri', 'id_nilai']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penilaians');
    }
};
