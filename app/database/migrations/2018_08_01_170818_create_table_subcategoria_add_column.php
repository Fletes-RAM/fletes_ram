<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSubcategoriaAddColumn extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bancos_subcategorias', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('categoria_id')->unsigned();
            $table->foreign('categoria_id')->references('id')->on('bancos_categorias')->onDelete('cascade');
            $table->string('subcategoria', 100);
            $table->timestamps();
        });

        Schema::table('bancos_movimientos', function ($table) {
            $table->string('subcategoria_id')->after('categoria_id');
        });

        Schema::table('bancos_prestamos', function ($table) {
            $table->string('subcategoria_id')->after('categoria_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bancos_movimientos', function ($table) {
            $table->dropColumn('subcategoria_id');
        });

        Schema::table('bancos_prestamos', function ($table) {
            $table->dropColumn('subcategoria_id');
        });

        Schema::drop('bancos_subcategorias');
    }
}
