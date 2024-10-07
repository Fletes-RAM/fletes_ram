<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

/**
*
*/
class Unidad extends Eloquent
{
    use SoftDeletingTrait;

    protected $table    = 'unidades';

    protected $fillable = ['unidad', 'tipo_de_unidad_id', 'placas', 'serie', 'poliza', 'aseguradora','vigencia','km_inicial','observaciones'];

    protected $dates    = ['deleted_at'];

    private $rules   = [
        "unidad"      => "required|min:3|max:15",
        "placas"      => "required|alpha_num|sometimes|unique:unidades",
        "serie"       => "required|alpha_num|sometimes|unique:unidades",
        "poliza"      => "alpha_dash|sometimes|unique:unidades",
        "aseguradora" => "min:3|max:50",
        "vigencia"    => "required",
    ];

    public function tipounidad()
    {
        return $this->belongsTo('TipoUnidad', 'tipo_de_unidad_id');
    }

    public function validate($data, $id=null)
    {
        if (isset($id)) {
            $this->rules['placas'] = 'required|sometimes|unique:unidades,placas,'.$id;
            $this->rules['serie']  = 'required|sometimes|unique:unidades,serie,'.$id;
            $this->rules['poliza'] = 'sometimes|unique:unidades,poliza,'.$id;
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
}
