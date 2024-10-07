<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bitacora extends Eloquent
{
  protected $table = 'bitacoras';

  protected $fillable = [

    'unidad_id', 'fecha', 'kilometraje', 'titulo', 'proveedor_id', 'costo', 'observaciones','llantas_marca', 'llanta_eje_direccion_der', 'llanta_eje_inter_der', 
    'llanta_eje_matriz_der', 'llanta_eje_direccion_izq', 'llanta_eje_inter_izq', 'llanta_eje_matriz_izq', 'balata_eje_direccion_der', 'balata_eje_inter_der', 
    'balata_eje_matriz_der', 'balata_eje_direccion_izq', 'balata_eje_inter_izq', 'balata_eje_matriz_izq', 'filtro_aire', 'filtro_aceite', 'filtro_diesel', 'filtro_agua', 
    'filtro_aceite_hidraulico', 'aceite_motor', 'aceite_caja_diferencial', 'aceite_caja', 'aceite_hidraulico', 'aceite_liquido_frenos', 'reparacion_especial', 'filtro_diesel_separador_agua',
    'filtro_diesel_separador_condimentos', 'filtro_adblue', 'anticongelante','filtro_secador_aire'

  ];
  private $rules = [
    'unidad_id'    => 'required',
    'fecha'        => 'required',
    'kilometraje'  => 'required',
    'titulo'       => 'required',
    'proveedor_id' => 'required',
    'costo'        => 'required'
  ];

  public function unidad()
  {
      return $this->belongsTo('Unidad', 'unidad_id');
  }

  public function proveedor()
  {
    return $this->belongsTo('CatProveedor', 'proveedor_id');
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
