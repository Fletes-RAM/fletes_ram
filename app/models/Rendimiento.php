<?php 

class Rendimiento extends Eloquent
{
	
	protected $table    = 'rendimientos';
	
	protected $fillable = ['tipo_de_unidad_id', 'rendimiento'];
	
	
	private $rules   = [
		"tipo_de_unidad_id" => "required",
		"rendimiento"       => "required",
	];

	public function tipounidad()
	{
		return $this->belongsTo('TipoUnidad', 'tipo_de_unidad_id');
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