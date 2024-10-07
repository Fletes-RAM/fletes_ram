<?php

/**
 *
 */
class ControlVehicular extends Eloquent
{
    protected $table = 'controles_vehiculares';

    protected $fillable = ['user_id', 'porcentaje', 'fecha', 'control_vehicular', 'origen', 'toneladas', 'tarifa', 'cantidad', 'iva'];

    private $rules = [
      'porcentaje'        => 'required',
      'fecha'             => 'required|date',
      'control_vehicular' => 'required',
      'origen'            => 'required',
      'toneladas'         => 'required',
      'tarifa'            => 'required',
      'cantidad'          => 'required'
    ];

    public function origenes()
    {
      return $this->belongsTo('Origen','origen','id');
    }

    public function users()
    {
      return $this->belongsTo('User','user_id');
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
