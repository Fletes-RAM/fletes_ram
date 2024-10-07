<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

/**
*
*/
class Banco extends Eloquent
{
    use SoftDeletingTrait;

    protected $table = 'bancos';

    protected $fillable = ['banco','no_cuenta','clabe','observaciones'];

    protected $dates = ['deleted_at'];

    private $rules  = [
    "banco"         => "required|min:3",
    "no_cuenta"     => "required|min:3|numeric|unique:bancos",
    "clabe"         => "required|min:3|unique:bancos"
  ];

    public function validate($data, $id=null)
    {
        if (isset($id)) {
            $this->rules['no_cuenta']     = 'required|min:3|numeric|unique:bancos,no_cuenta,'.$id;
            $this->rules['clabe']         = 'required|min:3|unique:bancos,clabe,'.$id;
        }
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

    public function sumatoria()
    {
        return $this->hasMany('BancoMovSum', 'bancos_id', 'id');
    }
}
