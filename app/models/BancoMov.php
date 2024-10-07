<?php


/**
*
*/
class BancoMov extends Eloquent
{
    protected $table = 'bancos_movimientos';

    protected $fillable = ['bancos_id','periodo','categoria_id','subcategoria_id','movimiento','folio','fecha','tipo','cantidad','observaciones'];


    private $rules = [
      "movimiento"   => "required|min:3",
      "cantidad"     => "required"
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
