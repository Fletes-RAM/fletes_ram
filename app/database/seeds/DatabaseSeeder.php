<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();


		//$this->call('ExerciseTableSeeder');

    $this->command->info('Llenando Tabla de codigos postales!');
		$path = 'app/developer_docs/codigos_postales.sql';
    DB::unprepared(file_get_contents($path));
    $this->command->info('Tabla de codigos postales llena!');

	}

}
