<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatProveedoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cat_proveedores', function($table){
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->string('proveedor', 200);
			$table->string('nombre_contacto', 200);
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
		Schema::drop('cat_proveedores');
	}

}
