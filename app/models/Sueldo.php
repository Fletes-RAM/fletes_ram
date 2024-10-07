<?php

/**
 * 
 */
class Sueldo extends Eloquent
{
	
	protected $table = 'sueldos';
	protected $fillable = ['operador_id', 'fecha_inicio', 'fecha_fin'];

	public function user()
	{
		return $this->belongsTo('User','user_id');
	}

	private $rules = [
		
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