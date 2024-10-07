<?php

class ApiController extends \BaseController {

	public function editaCliente()
	{
		/*return dump(Input::get('pk')); //cliente
		return dump(Input::get('value')); //operador*/

		$operador = Operador::find(Input::get('pk'));
		$operador->cliente_id = Input::get('value');
		$operador->save();

	}

	public function editaOrigen()
	{
		$operador = Operador::find(Input::get('pk'));
		$operador->origen = Input::get('value');
		$operador->save();
	}

	public function editaDestino()
	{
		$operador = Operador::find(Input::get('pk'));
		$operador->destino = Input::get('value');
		$operador->save();
	}

	public function editaEstatus()
	{
		$operador = Operador::find(Input::get('pk'));
		$operador->estatus = Input::get('value');
		$operador->save();
	}

}
