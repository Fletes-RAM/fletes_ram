<?php

/**
 *
 */
class BancoPresupuestoDetalle extends Eloquent
{
    protected $table = 'bancos_presupuesto_detalle';

    public function categoria()
    {
        return $this->belongsTo('BancoCategoria', 'categoria_id');
    }

    public function subcategoria()
    {
        return $this->belongsTo('BancoSubCategoria', 'subcategoria_id');
    }
}
