<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProveedoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('proveedores', function($table){
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->string('comprobante_id');
			$table->string('factura');
			$table->string('fecha');
			$table->string('valor_factura');
			$table->string('banco_id');
			$table->string('categoria_id');
			$table->string('subcategoria_id');
			$table->string('observaciones');
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
		Schema::drop('proveedores');
	}

}
