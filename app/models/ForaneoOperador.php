<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class ForaneoOperador extends Eloquent
{

	use SoftDeletingTrait;
	
	protected $table    = 'foraneos_operadores';
	protected $fillable = ['foraneo_operador'];
	protected $dates    = ['deleted_at'];

	private $rules = [
		'foraneo_operador' => 'required',
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