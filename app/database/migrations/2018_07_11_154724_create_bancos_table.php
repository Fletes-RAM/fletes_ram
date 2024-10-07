<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBancosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bancos', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('banco', 50);
            $table->string('no_cuenta', 25);
            $table->string('clabe', 25);
            $table->text('observaciones');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('bancos_saldos', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('bancos_id')->unsigned();
            $table->foreign('bancos_id')->references('id')->on('bancos')->onDelete('cascade');
            $table->string('periodo', 15);
            $table->double('saldo_inicial', 15, 2);
            $table->double('saldo_final', 15, 2);
            $table->boolean('cerrado');
            $table->timestamps();
        });

        Schema::create('bancos_movimientos', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('bancos_id')->unsigned();
            $table->foreign('bancos_id')->references('id')->on('bancos')->onDelete('cascade');
            $table->string('periodo', 15);
            $table->string('movimiento', 50);
            $table->string('tipo', 8);
            $table->double('cantidad', 15, 2);
            $table->text('observaciones');
            $table->timestamps();
        });

        Schema::create('bancos_prestamos', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('bancos_id')->unsigned();
            $table->foreign('bancos_id')->references('id')->on('bancos')->onDelete('cascade');
            $table->string('periodo', 15);
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('tipo', 8);
            $table->double('cantidad', 15, 2);
            $table->text('observaciones');
            $table->timestamps();
        });

        Schema::create('bancos_periodos', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('periodo', 25);
            $table->timestamps();
        });

        DB::statement('CREATE VIEW ram_bancos_list AS SELECT id, CONCAT(banco," | ",no_cuenta) as banco from ram_bancos ');
        DB::statement('CREATE VIEW ram_operadores_list AS SELECT id, CONCAT(first_name," ",last_name) as nombre FROM ram_users WHERE id in (SELECT user_id FROM ram_operadores) ');
        DB::statement('CREATE VIEW ram_bancos_movimientos_sum AS SELECT periodo,bancos_id,SUM(total) as total FROM (	SELECT periodo as periodo, bancos_id as bancos_id, SUM(cantidad*tipo) as total FROM ram_bancos_movimientos GROUP BY bancos_id,periodo UNION ALL SELECT periodo as periodo, bancos_id as bancos_id, SUM(cantidad*tipo) as total FROM ram_bancos_prestamos GROUP BY bancos_id,periodo) a GROUP BY bancos_id,periodo');
        DB::statement('INSERT INTO ram_bancos_periodos (periodo) VALUES ("Junio-2018")');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bancos_prestamos');
        Schema::drop('bancos_movimientos');
        Schema::drop('bancos_saldos');
        Schema::drop('bancos');
        Schema::drop('bancos_periodos');
        DB::statement('DROP VIEW ram_bancos_list');
        DB::statement('DROP VIEW ram_operadores_list');
        DB::statement('DROP VIEW ram_bancos_movimientos_sum');
    }
}
