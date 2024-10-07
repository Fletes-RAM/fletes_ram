<?php

/**
 *
 */
class BancoDetalle extends Eloquent
{
    protected $table = 'bancos_detalle';

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
