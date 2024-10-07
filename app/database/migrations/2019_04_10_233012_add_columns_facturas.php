<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsFacturas extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('facturas', function($table){
			$table->double('subtotal',15,2)->unsigned()->after('cotizacion_id');
			$table->double('maniobras',15,2)->unsigned()->after('subtotal');
			$table->double('otros',15,2)->unsigned()->after('maniobras');
			$table->double('iva',15,2)->unsigned()->after('otros');
			$table->double('retencion',15,2)->unsigned()->after('iva');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('facturas', function($table){
			$table->dropColumn('subtotal');
			$table->dropColumn('maniobras');
			$table->dropColumn('otros');
			$table->dropColumn('iva');
			$table->dropColumn('retencion');
		});
	}

}
