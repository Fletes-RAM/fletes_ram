<?php 

/**
* 
*/
class Estado extends Eloquent
{
	

	protected $table    = 'estados';
	
	public function Estados(){
    return $this->hasMany('Municipio',idEstado);
  }

  public function estadosOrigen(){
    return $this->hasMany('RutaDetalle','estado','idEstado');
  }


}