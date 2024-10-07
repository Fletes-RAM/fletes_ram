<?php


/**
*
*/
class BancoPrest extends Eloquent
{
    protected $table = 'bancos_prestamos';

    protected $fillable = ['bancos_id','periodo','categoria_id','subcategoria_id','movimiento','folio','fecha','user_id','tipo','cantidad','observaciones'];


    private $rules = [
      "cantidad"     => "required"
    ];

    public function categoria()
    {
        return $this->belongsTo('BancoCategoria', 'categoria_id');
    }

    public function subcategoria()
    {
        return $this->belongsTo('BancoSubCategoria', 'subcategoria_id');
    }

    public function user()
    {
        return $this->belongsTo('User', 'user_id');
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
