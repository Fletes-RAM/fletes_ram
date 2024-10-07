<?php

/**
 *
 */
class FacturaControl extends Eloquent
{

  protected $table    = 'facturas_controles';
  protected $fillable = ['control_id','cliente_id','subtotal','maniobras','iva','retencion','factura','total','observaciones','pagada','fecha_pago'];

  private $rules = [
    'control_id' => 'required',
    'cliente_id' => 'required',
    'factura'    => 'required|unique:facturas,factura',
    'subtotal'   => 'required',
    'maniobras'  => 'required',
    'iva'        => 'required',
    'retencion'  => 'required',
  ];

  public function control()
  {
    return $this->belongsTo('ControlVehicular', 'control_id');
  }

  public function cliente()
  { 
    return $this->belongsTo('Cliente');
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
