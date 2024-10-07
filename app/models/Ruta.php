<?php 

//use Illuminate\Database\Eloquent\SoftDeletingTrait;
/**
 * summary
 */
class Ruta extends Eloquent
{
    //use SoftDeletingTrait;

	protected $table    = 'nombres_rutas';
	
	protected $fillable = ['nombre', 'total_km', 'observaciones'];
	
	//protected $dates    = ['deleted_at'];
	
	private $rules = [
		"nombre"   => "required|unique:nombres_rutas",
		"total_km" => "required",
	];

	public function rutaDetalle(){
		return $this->hasMany('RutaDetalle','nombre_id');
	}

  public function validate($data,$id=null)
  {
  	if (isset($id)) {
  		$this->rules['nombre'] = 'required|unique:nombres_rutas,nombre,'.$id;
  	}
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