<?php 

use Illuminate\Database\Eloquent\SoftDeletingTrait;
/**
* 
*/
class TipoUnidad extends Eloquent
{
	
	use SoftDeletingTrait;

	protected $table    = 'tipos_de_unidades';
	
	protected $fillable = ['tipo_de_unidad', 'porcentaje', 'observaciones'];
	
	protected $dates    = ['deleted_at'];
	
	private $rules   = [
		"tipo_de_unidad" => "required|min:3|max:80",
		"porcentaje"     => "required",
	];

	private $messages = [
		""
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