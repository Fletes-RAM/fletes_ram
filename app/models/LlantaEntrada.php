<?php

class LlantaEntrada extends Eloquent
{
  protected $table = 'llantasentradas';
  protected $fillable = ['llanta_id','cantidad','precio','observaciones'];
  private $rules = [
    'llanta_id' => 'required',
    'cantidad' => 'required|numeric',
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
