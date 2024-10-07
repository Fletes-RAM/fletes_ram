<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
* 
*/
class Asistencia extends Eloquent
{

    protected $table = 'asistencias';
    protected $fillable = ['user_id', 'asistencia'];

    private $rules = [
        "user_id"    => "required",
        "asistencia" => "required",
    ];

    public function user(){
        return $this->belongsTo('User', 'user_id')->withTrashed();
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