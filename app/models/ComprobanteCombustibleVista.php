<?php


/**
 *
 */
class ComprobanteCombustibleVista extends Eloquent
{
    protected $table = 'vista_comprobantes_combustible';

    public function unidades()
    {
      return $this->belongsTo('Unidad','unidad_id');
    }

    public function gasolineras()
    {
      return $this->belongsTo('Gasolinera', 'gasolinera_id');
    }

    public function users()
    {
        return $this->belongsTo('User', 'user_id')->withTrashed();
    }
}
