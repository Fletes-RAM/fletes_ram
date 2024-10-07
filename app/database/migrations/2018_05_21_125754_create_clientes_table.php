<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('clientes', function($table){
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->string('cliente', 200);
			$table->string('nombre_contacto', 200);
			$table->string('email', 200);
			$table->string('telefono', 15);
			$table->string('gasto_admon', 10);
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
		Schema::drop('clientes');
	}

}
