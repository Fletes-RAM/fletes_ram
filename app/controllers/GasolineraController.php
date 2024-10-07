<?php

class GasolineraController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->layout        = View::make('catalogos.gasolinera.index');
		$this->layout->title = 'Gasolineras a Crédito';


    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Gasolineras a Crédito',
				'link'  => 'catalogos/gasolinera',
				'icon'  => 'fas fa-gas-pump'
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
		$this->layout        = View::make('catalogos.gasolinera.create');
		$this->layout->title = 'Gasolineras a Crédito';

    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Gasolineras a Crédito',
				'link'  => 'catalogos/gasolinera',
				'icon'  => 'fas fa-gas-pump'
      ),
      array(
				'title' => 'Nueva Gasolinera a Crédito',
				'link'  => '#',
				'icon'  => 'fas fa-plus'
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
		$new        = Input::all();
		$gasolinera = new Gasolinera();
		if (!$gasolinera->validate($new)) {
			$errors = $gasolinera->errors();
			return Redirect::route('newGasolinera')->withInput()->withErrors($errors);
		}

		$gasolinera->gasolinera    = Input::get('gasolinera');
		$gasolinera->estacion      = Input::get('estacion');
		$gasolinera->contacto      = Input::get('contacto');
		$gasolinera->email         = Input::get('email');
		$gasolinera->telefono      = Input::get('telefono');
		$gasolinera->observaciones = Input::get('observaciones');
		if ($gasolinera->save()) {
			return Redirect::route('listGasolinera')->with('success','Se ha agregado con éxito la nueva Gasolinera a Crédito.');
		}
		return Redirect::route('newGasolinera')->withInput()->withErrors($errors);
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
		$this->layout        = View::make('catalogos.gasolinera.create');
		$this->layout->title = 'Gasolinera a Crédito';
		$this->layout->gasolinera = Gasolinera::find($id);

    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Gasolinera a Crédito',
				'link'  => 'catalogos/gasolinera',
				'icon'  => 'fas fa-gas-pump'
      ),
      array(
				'title' => 'Editar Gasolinera a Crédito',
				'link'  => '#',
				'icon'  => 'fas fa-pencil-alt'
      ),
    );
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$gasolinera = Gasolinera::find($id);
		if (!$gasolinera->validate(Input::all())) {
			$errors = $gasolinera->errors();
			return Redirect::route('putGasolinera',$id)->withInput()->withErrors($errors);
		}

		$gasolinera->gasolinera    = Input::get('gasolinera');
		$gasolinera->estacion      = Input::get('estacion');
		$gasolinera->contacto      = Input::get('contacto');
		$gasolinera->email         = Input::get('email');
		$gasolinera->telefono      = Input::get('telefono');
		$gasolinera->observaciones = Input::get('observaciones');
		if ($gasolinera->save()) {
			return Redirect::route('listGasolinera')->with('success','Se han guardado con éxito los cambios	.');
		}
		return Redirect::route('putGasolinera',$id)->withInput()->withErrors($errors);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$gasolinera = Gasolinera::destroy($id);
		return Redirect::route('listGasolinera')->with('info','Se ha eliminado la Gasolinera a Crédito');
	}


}
