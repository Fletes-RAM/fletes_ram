<?php

/**
 *
 */
class AsignacionEspecial extends Eloquent
{
    protected $table = 'asignaciones_especiales';
    protected $fillable = ['unidad_id','user_id','fecha','gasolinera_id','ticket','litros','precio','total','kilometraje','rendimiento','foto_ticket','foto_tablero_antes','foto_tablero_despues','foto_tablero_km'];

    private $rules = [
      'unidad_id'            => 'required',
      'fecha'                => 'required|date',
      'gasolinera_id'        => 'required',
      'ticket'               => 'required|unique:asignaciones_especiales,ticket',
      'litros'               => 'required|numeric',
      //'precio'               => 'required|numeric',
      'kilometraje'          => 'required|numeric',
      'foto'                 => 'required|mimes:jpeg,bmp,png,jpg',
      'foto2'                => 'required|mimes:jpeg,bmp,png,jpg',
      'foto3'                => 'required|mimes:jpeg,bmp,png,jpg',
      'foto4'                => 'required|mimes:jpeg,bmp,png,jpg'
    ];

    public function user()
    {
        return $this->belongsTo('User', 'user_id')->withTrashed();
    }

    public function unidad()
    {
        return $this->belongsTo('Unidad', 'unidad_id');
    }

    public function gasolinera()
    {
        return $this->belongsTo('Gasolinera', 'gasolinera_id');
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
