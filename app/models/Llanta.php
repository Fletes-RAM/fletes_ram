<?php

class Llanta extends Eloquent
{
  protected $table = 'llantas';
  protected $fillable = ['clave','marca','medida','tipo','existencia'];
  private $rules = [
    'clave' => 'required',
    'marca' => 'required',
    'medida' => 'required',
    'tipo' => 'required',
    'existencia' => 'required'
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
