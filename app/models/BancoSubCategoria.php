<?php

/**
 *
 */
class BancoSubCategoria extends Eloquent
{
    protected $table = "bancos_subcategorias";

    protected $fillable = ['categoria_id','subcategoria'];

    private $rules = [
    'subcategoria' => 'required|min:3'
    ];

    public function categorias()
    {
        return $this->belongsTo('BancoCategoria', 'categoria_id');
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
