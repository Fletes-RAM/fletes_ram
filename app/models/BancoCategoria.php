<?php

/**
 *
 */
class BancoCategoria extends Eloquent
{
    protected $table = "bancos_categorias";

    protected $fillable = ['categoria'];

    private $rules = [
    'categoria' => 'required|min:3'
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
