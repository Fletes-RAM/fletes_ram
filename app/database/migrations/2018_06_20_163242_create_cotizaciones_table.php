<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCotizacionesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizaciones', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('folio', 20);
            $table->integer('cliente_id')->unsigned();
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->integer('ruta_id')->unsigned();
            $table->foreign('ruta_id')->references('id')->on('nombres_rutas')->onDelete('cascade');
            $table->integer('tipo_de_unidad_id')->unsigned();
            $table->foreign('tipo_de_unidad_id')->references('id')->on('tipos_de_unidades')->onDelete('cascade');
            $table->integer('rendimiento_id')->unsigned();
            $table->foreign('rendimiento_id')->references('id')->on('rendimientos')->onDelete('cascade');
            $table->string('tot_km', 10);
            $table->double('costo_combustible', 15, 2)->unsigned();
            $table->double('propuesta', 15, 2)->unsigned();
            $table->double('utilidad', 15, 2)->unsigned();
            $table->double('sueldo_ope', 15, 2)->unsigned();
            $table->double('gastos_admon', 15, 2)->unsigned();
            $table->double('otros_gastos', 15, 2)->unsigned();
            $table->double('combustible', 15, 2)->unsigned();
            $table->double('caseta', 15, 2)->unsigned();
            $table->text('observaciones');
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
        Schema::drop('cotizaciones');
    }
}
