<?php

/**
*
*/
class Origen extends Eloquent
{

    protected $table = 'origenes';

    protected $fillable = ['origen'];


    private $rules  = [
    "origen"         => "required|min:3",
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
