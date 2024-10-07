<?php
/**
 *
 */
class Asignacion extends Eloquent
{
    protected $table = 'asignaciones';
    protected $fillable = ['cotizacion_id','user_id','unidad_id','terminado'];

    private $rules = [
      'cotizacion_id' => 'required',
      'user_id'       => 'required',
      'unidad_id'     => 'required',
    ];

    public function cotizacion()
    {
      return $this->belongsTo('Cotizacion', 'cotizacion_id')->withTrashed();
    }

    public function operador()
    {
      return $this->belongsTo('User', 'user_id')->withTrashed();
    }

    public function unidad()
    {
      return $this->belongsTo('Unidad', 'unidad_id');
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
