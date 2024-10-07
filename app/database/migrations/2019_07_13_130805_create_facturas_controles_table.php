<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasControlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('facturas_controles', function($table){
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->integer('control_id')->unsigned();
			$table->foreign('control_id')->references('id')->on('controles_vehiculares')->onDelete('cascade');
			$table->integer('cliente_id')->unsigned();
			$table->double('subtotal',15,2)->unsigned();
			$table->double('maniobras',15,2)->unsigned();
			$table->double('otros',15,2)->unsigned();
			$table->double('iva',15,2)->unsigned();
			$table->double('retencion',15,2)->unsigned();
			$table->string('factura');
			$table->double('total', 15, 2)->unsigned();
			$table->text('observaciones');
			$table->boolean('pagada');
			$table->date('fecha_pago');
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
		Schema::drop('facturas_controles');
	}

}
