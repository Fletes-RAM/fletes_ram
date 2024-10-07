<?php

class BitacoraController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->layout = View::make('sistema.bitacora.index');
		$this->layout->title = 'Bitácora';
		$this->layout->unidad = Unidad::find(Input::get('unidad_id'));
		$this->layout->bitacoras = Bitacora::where('unidad_id',Input::get('unidad_id'))->orderBy('fecha','desc')->get();

		// add breadcrumb to current page
		$this->layout->breadcrumb = array(
			array(
								'title' => 'Inicio',
								'link'  => '/',
								'icon'  => 'fas fa-home'
			),
			array(
								'title' => 'Bitácora',
								'link'  => 'sistema/bitacora',
								'icon'  => 'fas fa-book'
			),
		);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$this->layout = View::make('sistema.bitacora.create');
		$this->layout->title = 'Bitácora';
		$this->layout->unidad = Unidad::find(Input::get('unidad_id'));
		$this->layout->proveedores    = CatProveedor::lists('proveedor','id');
		$this->layout->unidad_id = Input::get('unidad_id');
		// add breadcrumb to current page
		$this->layout->breadcrumb = array(
			array(
								'title' => 'Inicio',
								'link'  => '/',
								'icon'  => 'fas fa-home'
			),
			array(
								'title' => 'Bitácora',
								'link'  => 'sistema/bitacora',
								'icon'  => 'fas fa-book'
			),
		);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		
		$new = Input::all();
		$bitacora = new Bitacora();
		if(!$bitacora->validate($new)) {
			$errors = $bitacora->errors();
			return Redirect::back()->withInput()->withErrors($errors);
		}

		if (Input::has('llanta_eje_direccion_der')) {
			Input::merge(['llanta_eje_direccion_der' => 1]);
		}

		if (Input::has('llanta_eje_inter_der')) {
			Input::merge(['llanta_eje_inter_der' => 1]);
		}

		if (Input::has('llanta_eje_matriz_der')) {
			Input::merge(['llanta_eje_matriz_der' => 1]);
		}

		if (Input::has('llanta_eje_direccion_izq')) {
			Input::merge(['llanta_eje_direccion_izq' => 1]);
		}

		if (Input::has('llanta_eje_inter_izq')) {
			Input::merge(['llanta_eje_inter_izq' => 1]);
		}

		if (Input::has('llanta_eje_matriz_izq')) {
			Input::merge(['llanta_eje_matriz_izq' => 1]);
		}

		if (Input::has('balata_eje_direccion_der')) {
			Input::merge(['balata_eje_direccion_der' => 1]);
		}

		if (Input::has('balata_eje_inter_der')) {
			Input::merge(['balata_eje_inter_der' => 1]);
		}

		if (Input::has('balata_eje_matriz_der')) {
			Input::merge(['balata_eje_matriz_der' => 1]);
		}

		if (Input::has('balata_eje_direccion_izq')) {
			Input::merge(['balata_eje_direccion_izq' => 1]);
		}

		if (Input::has('balata_eje_inter_izq')) {
			Input::merge(['balata_eje_inter_izq' => 1]);
		}

		if (Input::has('balata_eje_matriz_izq')) {
			Input::merge(['balata_eje_matriz_izq' => 1]);
		}

		if (Input::has('filtro_aire')) {
			Input::merge(['filtro_aire' => 1]);
		}

		if (Input::has('filtro_aceite')) {
			Input::merge(['filtro_aceite' => 1]);
		}

		if (Input::has('filtro_diesel')) {
			Input::merge(['filtro_diesel' => 1]);
		}

		if (Input::has('filtro_agua')) {
			Input::merge(['filtro_agua' => 1]);
		}

		if (Input::has('filtro_aceite_hidraulico')) {
			Input::merge(['filtro_aceite_hidraulico' => 1]);
		}

		if (Input::has('aceite_motor')) {
			Input::merge(['aceite_motor' => 1]);
		}

		if (Input::has('aceite_caja_diferencial')) {
			Input::merge(['aceite_caja_diferencial' => 1]);
		}

		if (Input::has('aceite_caja')) {
			Input::merge(['aceite_caja' => 1]);
		}

		if (Input::has('aceite_hidraulico')) {
			Input::merge(['aceite_hidraulico' => 1]);
		}

		if (Input::has('aceite_liquido_frenos')) {
			Input::merge(['aceite_liquido_frenos' => 1]);
		}

		if (Input::has('llanta_eje_direccion_der')) {
			Input::merge(['llanta_eje_direccion_der' => 1]);
		}

		if (Input::has('filtro_diesel_separador_agua')) {
			Input::merge(['filtro_diesel_separador_agua' => 1]);
		}

		if (Input::has('filtro_diesel_separador_condimentos')) {
			Input::merge(['filtro_diesel_separador_condimentos' => 1]);
		}

		if (Input::has('filtro_adblue')) {
			Input::merge(['filtro_adblue' => 1]);
		}

        if (Input::has('filtro_secador_aire')) {
			Input::merge(['filtro_secador_aire' => 1]);
		}

		if (Input::has('anticongelante')) {
			Input::merge(['anticongelante' => 1]);
		}

		//return dump(Input::all());

		Bitacora::create(Input::all());
		return Redirect::route('bitacora.index',['unidad_id'=>Input::get('unidad_id')])->with('success','Se ha creado la Bitácora con éxito.');
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
		//
	}


}
