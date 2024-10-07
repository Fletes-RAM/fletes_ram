<?php 

//use Illuminate\Database\Eloquent\SoftDeletingTrait;
/**
 * summary
 */
class RutaDetalle extends Eloquent
{
    //use SoftDeletingTrait;

	protected $table    = 'detalles_rutas';
	
	protected $fillable = ['nombre_id', 'estado', 'origen', 'estado_destino', 'destino', 'km'];
	
	//protected $dates    = ['deleted_at'];
	

}