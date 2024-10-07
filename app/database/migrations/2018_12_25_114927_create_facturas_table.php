<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('facturas_cotizaciones', function($table){
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->integer('cotizacion_id')->unsigned();
			$table->foreign('cotizacion_id')->references('id')->on('cotizaciones')->onDelete('cascade');
			$table->string('factura');
			$table->double('factura_num', 15, 2)->unsigned();
			$table->date('fecha_pago');
			$table->text('observaciones');
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
		Schema::drop('facturas_cotizaciones');
	}

}
