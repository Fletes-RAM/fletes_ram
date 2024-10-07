<?php


/**
*
*/
class BancoPeriodo extends Eloquent
{
    protected $table = 'bancos_periodos';

    protected $fillable = ['periodo'];


    private $rules = [
      "periodo"   => "required|min:3",
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
