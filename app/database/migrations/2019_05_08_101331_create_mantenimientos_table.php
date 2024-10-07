<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMantenimientosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mantenimientos', function($table){
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->string('factura');
			$table->date('fecha');
			$table->string('plazo');
			$table->double('cantidad', 15, 2);
			$table->string('descuento');
			$table->integer('unidad_id')->unsigned();
			$table->foreign('unidad_id')->references('id')->on('unidades')->onDelete('cascade');
			$table->string('status', 7);
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
		Schema::drop('mantenimientos');
	}

}
