<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;
/**
*
*/
class Operador extends Eloquent
{

	use SoftDeletingTrait;

	protected $table    = 'operadores';

	protected $fillable = ['user_id', 'nss', 'telefono', 'contacto', 'tel_contacto','vigencia','medica','unidad_id', 'observaciones'];

	protected $dates    = ['deleted_at'];

	private $rules      = [
		"nss"           => "required|min:11|max:11|unique:operadores",
		"telefono"      => "required",
		"contacto"      => "required",
		"vigencia"			=> 'required',
		"medica"  			=> 'required',
		"unidad_id"			=> 'required',
		"tel_contacto"  => "required",
	];

	public function user()
	{
		return $this->belongsTo('User')->withTrashed();
	}

	public function unidad()
	{
		return $this->belongsTo('Unidad','unidad_id')->withTrashed();
	}


  public function validate($data,$id=null)
  {
  	if (isset($id)) {
  		$this->rules['nss'] = 'min:11|max:11|unique:operadores,nss,'.$id;
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
