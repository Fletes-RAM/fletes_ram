<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

/**
 * 
 */
class Foraneo extends Eloquent
{
	
	use SoftDeletingTrait;

	protected $table    = 'foraneos';
	protected $fillable = ['foraneo_operador_id','unidad_id','fecha','concepto','tipo','monto','tp','saldo'];
	protected $dates    = ['deleted_at'];

	private $rules = [
		'foraneo_operador_id' => 'required',
		'unidad_id'           => 'required',
		'fecha'               => 'required',
		'concepto'            => 'required',
		'tipo'                => 'required',
		'monto'               => 'required',
		'tp'                  => 'required',
	];

	public function validate($data)
  {
  	
  	//hace un validador nuevo
			$v = Validator::make($data, $this->rules);
			//checa validación
			if ($v->fails()) {
				//asigna errores y regresa falso
				$this->errors = $v->errors();
				return false;
			}

			//la validación pasa
			return true;

  }

	public function errors()
	{
		return $this->errors;
	}

}