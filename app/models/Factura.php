<?php

/**
 *
 */
class Factura extends Eloquent
{

  protected $table    = 'facturas';
  protected $fillable = ['cotizacion_id','subtotal','maniobras','iva','retencion','factura','total','observaciones','pagada','fecha_pago'];

  private $rules = [
    'cotizacion_id' => 'required',
    'factura'       => 'required|unique:facturas,factura',
    'subtotal'      => 'required',
    'maniobras'     => 'required',
    'iva'           => 'required',
    'retencion'     => 'required',
  ];

  public function cotizacion()
  {
    return $this->belongsTo('Cotizacion', 'cotizacion_id')->withTrashed();
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
