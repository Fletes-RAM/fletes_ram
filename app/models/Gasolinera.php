<?php 

use Illuminate\Database\Eloquent\SoftDeletingTrait;
/**
* 
*/
class Gasolinera extends Eloquent
{
	
	use SoftDeletingTrait;

	protected $table    = 'gasolineras';
	
	protected $fillable = ['gasolinera', 'contacto', 'email', 'telefono', 'observaciones'];
	
	protected $dates    = ['deleted_at'];
	
	private $rules   = [
		"gasolinera" => "required|min:3|max:80",
		"contacto"     => "min:3",
		"email" => "email",
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