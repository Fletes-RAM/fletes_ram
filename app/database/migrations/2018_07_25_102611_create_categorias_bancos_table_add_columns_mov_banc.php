<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriasBancosTableAddColumnsMovBanc extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bancos_categorias', function ($table) {
            $table->engine = "InnoDB";
            $table->increments('id')->unsigned();
            $table->string('categoria', 100);
            $table->timestamps();
        });

        Schema::table('bancos_movimientos', function ($table) {
            $table->integer('categoria_id')->unsigned()->after('periodo');
            $table->string('folio', 50)->after('movimiento');
            $table->date('fecha')->after('folio');
        });

        Schema::table('bancos_prestamos', function ($table) {
            $table->integer('categoria_id')->unsigned()->after('periodo');
            $table->string('movimiento', 50)->after('categoria_id');
            $table->string('folio', 50)->after('movimiento');
            $table->date('fecha')->after('folio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bancos_categorias');

        Schema::table('bancos_movimientos', function ($table) {
            $table->dropColumn('categoria_id');
            $table->dropColumn('folio');
            $table->dropColumn('fecha');
        });

        Schema::table('bancos_prestamos', function ($table) {
            $table->dropColumn('categoria_id');
            $table->dropColumn('movimiento');
            $table->dropColumn('folio');
            $table->dropColumn('fecha');
        });
    }
}
