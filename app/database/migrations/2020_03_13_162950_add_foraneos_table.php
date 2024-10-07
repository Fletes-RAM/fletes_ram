<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForaneosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('foraneos_operadores', function($table){
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->string('foraneo_operador', 250);
			$table->softDeletes();
			$table->timestamps();
		});

		Schema::create('foraneos', function($table){
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->integer('foraneo_operador_id')->unsigned();
			$table->foreign('foraneo_operador_id')->references('id')->on('foraneos_operadores')->onDelete('cascade');
			$table->integer('unidad_id')->unsigned();
			$table->date('fecha');
			$table->string('concepto', 250);
			$table->string('tipo', 250);
			$table->double('monto', 15, 2);
			$table->string('tp',8);
			$table->double('saldo', 15, 2);
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
		Schema::drop('foraneos');
		Schema::drop('foraneos_operadores');
	}

}
