<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRendimientosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rendimientos', function($table){
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->integer('tipo_de_unidad_id')->unsigned();
			$table->foreign('tipo_de_unidad_id')->references('id')->on('tipos_de_unidades')->onDelete('cascade');
			$table->string('rendimiento', 10);
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
		Schema::drop('rendimientos');
	}

}
