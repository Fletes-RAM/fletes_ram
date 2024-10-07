<?php

/**
 *
 */
class BancoPresupuesto extends Eloquent
{
    protected $table = 'bancos_presupuesto';

    public function categoria()
    {
        return $this->belongsTo('BancoCategoria', 'categoria_id');
    }

    public function banco()
    {
        return $this->belongsTo('Banco', 'bancos_id');
    }
}
