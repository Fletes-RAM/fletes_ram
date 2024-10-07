<?php

class InventarioMaterial extends Eloquent
{
  protected $table = 'inventariosmateriales';
  protected $fillable = ['nombre','descripcion','precio','existencia','valor'];
  private $rules = [
    'nombre' => 'required',
    'descripcion' => 'required',
    'precio' => 'required|numeric',
    'existencia' => 'required|numeric'
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
