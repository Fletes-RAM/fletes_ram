<?php

class ForaneoOperadorController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->layout        = View::make('sistema.foraneo_operador.index');
		$this->layout->title = 'Operadores Foráneos';
		$this->layout->operadores = ForaneoOperador::all();


    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Operadores Foráneos',
				'link'  => '#',
				'icon'  => 'fas fa-users'
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
		$this->layout        = View::make('sistema.foraneo_operador.create');
		$this->layout->title = 'Operadores Foráneos';

    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Operadores Foráneos',
				'link'  => 'operador_foraneo',
				'icon'  => 'fas fa-users'
      ),
      array(
				'title' => 'Nuevo Operador Foráneo',
				'link'  => '#',
				'icon'  => 'fas fa-user'
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
		$foraneo_operador = new ForaneoOperador();

		if(!$foraneo_operador->validate($new)) {
			$errors = $foraneo_operador->errors();
			return Redirect::back()->withInput()->withErrors($errors);
		}

		ForaneoOperador::create(Input::all());
		return Redirect::route('foraneo_operador.index')->with('success','Se ha creado el Operador Foráneo con éxito');
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
		
		$this->layout             = View::make('sistema.foraneo_operador.create');
		$this->layout->title      = 'Operadores Foráneos';
		$this->layout->operador   = ForaneoOperador::find($id);
		
		// add breadcrumb to current page
		$this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Operadores Foráneos',
				'link'  => 'operador_foraneo',
				'icon'  => 'fas fa-users'
      ),
      array(
				'title' => 'Nuevo Operador Foráneo',
				'link'  => '#',
				'icon'  => 'fas fa-user'
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

		$foraneo_operador = ForaneoOperador::find($id);
		$edit = Input::all();

		if(!$foraneo_operador->validate($edit)) {
			$errors = $foraneo_operador->errors();
			return Redirect::back()->withInput()->withErrors($errors);
		}

		$foraneo_operador->foraneo_operador = Input::get('foraneo_operador');
		$foraneo_operador->save();
		return Redirect::route('foraneo_operador.index')->with('success','Se ha actualizado el Operador Foráneo con éxito');

	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		ForaneoOperador::destroy($id);
		return Redirect::route('foraneo_operador.index')->with('info','Se ha eliminado el Operador Foráneo');
	}


}
