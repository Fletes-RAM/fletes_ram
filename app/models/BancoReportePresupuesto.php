<?php

/**
 *
 */
class BancoReportePresupuesto extends Eloquent
{
    protected $table = 'bancos_reporte_presupuesto';

    public function banco()
    {
        return $this->belongsTo('Banco', 'bancos_id');
    }

    public function categoria()
    {
        return $this->belongsTo('BancoCategoria', 'categoria_id');
    }

    public function subcategoria()
    {
        return $this->belongsTo('BancoSubCategoria', 'subcategoria_id');
    }
}
