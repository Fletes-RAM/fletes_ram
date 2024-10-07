<?php

/**
 *
 */
class Proveedor extends Eloquent
{
    protected $table = 'proveedores';

    protected $fillable = ['comprobante_id', 'factura', 'fecha', 'valor_factura', 'banco_id', 'categoria_id', 'subcategoria_id', 'observaciones'];

    private $rules = [
      'factura'         => 'required',
      'fecha'           => 'required|date',
      'valor_factura'   => 'required',
      'banco_id'        => 'required',
      'categoria_id'    => 'required',
      'subcategoria_id' => 'required',
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
