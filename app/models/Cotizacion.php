<?php 

use Illuminate\Database\Eloquent\SoftDeletingTrait;
/**
* 
*/
class Cotizacion extends Eloquent
{
	
	use SoftDeletingTrait;

	protected $table    = 'cotizaciones';
	
	protected $fillable = ['cliente_id','ruta_id','tipo_de_unidad_id','rendimiento_id','tot_km','tipo_combustible','costo_combustible','utilidad','sueldo_ope','gastos_admon','otros_gastos','combustible','caseta','observaciones'];
	
	protected $dates    = ['deleted_at'];
	
	private $rules   = [
		'tot_km'            => 'numeric',
		'costo_combustible' => 'required',
		'propuesta'          => 'required|numeric',
		'utilidad'          => 'required|numeric',
		'sueldo_ope'        => 'required|numeric',
		'gastos_admon'      => 'required|numeric',
		'otros_gastos'      => 'numeric',
		'combustible'       => 'required|numeric',
		'caseta'            => 'numeric'
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

	public function cliente()
	{	
		return $this->belongsTo('Cliente');
	}

	public function tipounidad()
	{
		return $this->belongsTo('TipoUnidad', 'tipo_de_unidad_id');
	}

	public function ruta()
	{
		return $this->belongsTo('Ruta');
	}

}