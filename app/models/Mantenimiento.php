<?php
use Carbon\Carbon;
/**
 * 
 */
class Mantenimiento extends Eloquent
{
	
	protected $table = 'mantenimientos';
	protected $fillable = ['factura','fecha','plazo','cantidad','descuento','unidad_id','proveedor_id'];
  protected $dates = ['fecha'];

	public function unidad()
  {
    return $this->belongsTo('Unidad', 'unidad_id');
  }

  public function proveedor()
  {
    return $this->belongsTo('CatProveedor', 'proveedor_id');
  }

  private $rules = [
    'factura'   => 'required',
    'fecha'     => 'required',
    'plazo'     => 'required',
    'cantidad'  => 'required',
    'unidad_id' => 'required',
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