<?php

class GastoController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->layout = View::make('sistema.gasto.index');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$this->layout           = View::make('sistema.gasto.create');
		$this->layout->operador = Input::get('operador');
		$this->layout->fecha1   = Input::get('fecha1');
		$this->layout->fecha2   = Input::get('fecha2');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$new = Input::all();
		$gasto = new Gasto();

		if (!$gasto->validate($new)) {
			$errors = $gasto->errors();
			return Redirect::route('gasto.create', ['operador'=>$operador,'fecha1'=>$fecha1,'fecha2'=>$fecha2])->withInput()->with('error', 'No ha capturado ningun dato');
		}

		$gasto->create($new);
		return Redirect::route('gasto.index')->with('success', 'Cambios Guardados. Hacer click en el boton cerrar para refrescar cambios');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Gasto::destroy($id);
		return Redirect::back()->with('info','Se ha eliminado el comprobante de gasto.');
	}


}
