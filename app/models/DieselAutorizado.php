<?php

class DieselAutorizado extends Eloquent
{
    protected $table = 'diesel_autorizado';
    protected $fillable = ['tipo_de_unidad_id', 'origen', 'destino', 'lts_aut'];

    private $rules = [
        'tipo_de_unidad_id' => 'required',
        'origen'            => 'required',
        'destino'           => 'required',
        'lts_aut'           => 'required|numeric',
    ];

    public function tipo()
    {
        return $this->belongsTo('TipoUnidad', 'tipo_de_unidad_id', 'id');
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
