<?php

/**
 *
 */
class Gasto extends Eloquent
{

  protected $table = 'comprobantes_gastos';
  protected $fillable = ['user_id', 'fecha', 'descripcion', 'factura', 'total', 'observaciones'];

  private $rules = [
    'fecha'       => 'required',
    'descripcion' => 'required',
    'factura'     => 'required',
    'total'       => 'required'
  ];

  public function user()
  {
    return $this->belongsTo('User');
  }

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
