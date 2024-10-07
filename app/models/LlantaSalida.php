<?php

class LlantaSalida extends Eloquent 
{
  protected $table = 'llantassalidas';
  protected $fillable = ['llanta_id','unidad_id','cantidad','observaciones'];
  private $rules = [
    'llanta_id' => 'required',
    'unidad_id' => 'required',
    'cantidad' => 'required|numeric'
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

	public function unidad()
	{
		return $this->belongsTo('Unidad','unidad_id')->withTrashed();
	}
}
