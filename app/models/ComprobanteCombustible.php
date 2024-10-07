<?php

/**
 *
 */
class ComprobanteCombustible extends Eloquent
{
    protected $table = 'comprobantes_combustible';

    protected $fillable = ['user_id', 'combustible_id', 'observaciones'];

    private $rules = [
      'combustible_id' => 'required',
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
