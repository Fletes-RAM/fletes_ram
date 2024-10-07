<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCostosCombustiblesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('costos_combustibles', function($table){
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->string('combustible', 15);
			$table->double('costo', 10, 2);
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
		Schema::drop('costos_combustibles');
	}

}
