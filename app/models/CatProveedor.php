<?php 

use Illuminate\Database\Eloquent\SoftDeletingTrait;
/**
* 
*/
class CatProveedor extends Eloquent
{
	
	use SoftDeletingTrait;

	protected $table    = 'cat_proveedores';
	
	protected $fillable = ['proveedor', 'nombre_contacto', 'email', 'telefono', 'observaciones'];
	
	protected $dates    = ['deleted_at'];
	
	private $rules      = [
		"proveedor"       => "required|min:3|max:80",
		"nombre_contacto" => "required|min:3",
		"email"           => "required|email",
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