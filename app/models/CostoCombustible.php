<?php 

/**
* 
*/
class CostoCombustible extends Eloquent
{
	

	protected $table    = 'costos_combustibles';
	
	protected $fillable = ['combustible', 'costo'];
	
	
	private $rules   = [
		"costo" => "required",
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