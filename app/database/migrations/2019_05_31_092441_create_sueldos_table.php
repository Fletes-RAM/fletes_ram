<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSueldosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sueldos', function($table){
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->integer('operador_id')->unsigned();
			$table->foreign('operador_id')->references('id')->on('users')->onDelete('cascade');
			$table->date('fecha_inicio');
			$table->date('fecha_fin');
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
		Schema::drop('sueldos');
	}

}
