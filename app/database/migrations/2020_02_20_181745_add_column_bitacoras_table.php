<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnBitacorasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bitacoras', function($table){
			$table->string('llantas_marca')->after('observaciones');
			$table->boolean('llanta_eje_direccion_der')->after('llantas_marca');
			$table->boolean('llanta_eje_inter_der')->after('llanta_eje_direccion_der');
			$table->boolean('llanta_eje_matriz_der')->after('llanta_eje_inter_der');
			$table->boolean('llanta_eje_direccion_izq')->after('llanta_eje_matriz_der');
			$table->boolean('llanta_eje_inter_izq')->after('llanta_eje_direccion_izq');
			$table->boolean('llanta_eje_matriz_izq')->after('llanta_eje_inter_izq');

			$table->boolean('balata_eje_direccion_der')->after('llanta_eje_matriz_izq');
			$table->boolean('balata_eje_inter_der')->after('balata_eje_direccion_der');
			$table->boolean('balata_eje_matriz_der')->after('balata_eje_inter_der');
			$table->boolean('balata_eje_direccion_izq')->after('balata_eje_matriz_der');
			$table->boolean('balata_eje_inter_izq')->after('balata_eje_direccion_izq');
			$table->boolean('balata_eje_matriz_izq')->after('balata_eje_inter_izq');

			$table->boolean('filtro_aire')->after('balata_eje_matriz_izq');
			$table->boolean('filtro_aceite')->after('filtro_aire');
			$table->boolean('filtro_diesel')->after('filtro_aceite');
			$table->boolean('filtro_agua')->after('filtro_diesel');
			$table->boolean('filtro_aceite_hidraulico')->after('filtro_agua');

			$table->boolean('aceite_motor')->after('filtro_aceite_hidraulico');
			$table->boolean('aceite_caja_diferencial')->after('aceite_motor');
			$table->boolean('aceite_hidraulico')->after('aceite_caja_diferencial');
			$table->boolean('aceite_liquido_frenos')->after('aceite_hidraulico');

			$table->text('reparacion_especial')->after('aceite_liquido_frenos');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bitacoras', function($table){
			$table->dropColumn('llantas_marca');
			$table->dropColumn('llanta_eje_direccion_der');
			$table->dropColumn('llanta_eje_inter_der');
			$table->dropColumn('llanta_eje_matriz_der');
			$table->dropColumn('llanta_eje_direccion_izq');
			$table->dropColumn('llanta_eje_inter_izq');
			$table->dropColumn('llanta_eje_matriz_izq');

			$table->dropColumn('balata_eje_direccion_der');
			$table->dropColumn('balata_eje_inter_der');
			$table->dropColumn('balata_eje_matriz_der');
			$table->dropColumn('balata_eje_direccion_izq');
			$table->dropColumn('balata_eje_inter_izq');
			$table->dropColumn('balata_eje_matriz_izq');

			$table->dropColumn('filtro_aire');
			$table->dropColumn('filtro_aceite');
			$table->dropColumn('filtro_diesel');
			$table->dropColumn('filtro_agua');
			$table->dropColumn('filtro_aceite_hidraulico');

			$table->dropColumn('aceite_motor');
			$table->dropColumn('aceite_caja_diferencial');
			$table->dropColumn('aceite_hidraulico');
			$table->dropColumn('aceite_liquido_frenos');

			$table->dropColumn('reparacion_especial');
		});
	}

}
