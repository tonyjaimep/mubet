<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paradas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_ruta');
            $table->unsignedBigInteger('id_estacion');
            $table->unsignedBigInteger('id_parada_siguiente')->nullable();
            $table->unsignedBigInteger('id_parada_anterior')->nullable();

            $table->foreign('id_ruta')
                ->references('id')
                ->on('rutas')
                ->onDelete('cascade');
            $table->foreign('id_estacion')
                ->references('id')
                ->on('estaciones')
                ->onDelete('cascade');
            $table->foreign('id_parada_siguiente')
                ->references('id')
                ->on('paradas')
                ->onDelete('set null');
            $table->foreign('id_parada_anterior')
                ->references('id')
                ->on('paradas')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paradas');
    }
}
