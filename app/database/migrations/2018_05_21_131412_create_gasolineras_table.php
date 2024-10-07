<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGasolinerasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gasolineras', function($table){
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->string('gasolinera',200);
			$table->string('contacto', 200);
			$table->string('email', 200);
			$table->string('telefono', 15);
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
		Schema::drop('gasolineras');
	}

}
