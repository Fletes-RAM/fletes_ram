<?php

class InventarioMaterialEntrada extends Eloquent
{
  protected $table = 'inventariosmaterialesentradas';
  protected $fillable = ['inventariomaterial_id','cantidad','precio','observaciones'];
  private $rules = [
    'inventariomaterial_id' => 'required',
    'cantidad' => 'required|numeric',
    'precio' => 'required|numeric'
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
