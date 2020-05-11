<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusquedasParadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('busquedas_paradas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_busqueda');
            $table->unsignedBigInteger('id_parada');

            $table->foreign('id_busqueda')->references('id')->on('busquedas')->onDelete('cascade');
            $table->foreign('id_parada')->references('id')->on('paradas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('busquedas_paradas');
    }
}
