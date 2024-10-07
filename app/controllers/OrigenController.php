<?php

class OrigenController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->layout           = View::make('catalogos.origen.index');
		$this->layout->title    = 'Control Vehicular Origen';

		// add breadcrumb to current page
		$this->layout->breadcrumb = array(
		              array(
		    'title' => 'Inicio',
		    'link'  => '/',
		    'icon'  => 'fas fa-home'
		              ),
		              array(
		    'title' => 'Control Vehicular Origen',
		    'link'  => 'catalogos/origen',
		    'icon'  => 'fas fa-check'
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
		$this->layout           = View::make('catalogos.origen.create');
		$this->layout->title    = 'Nuevo Origen';

		// add breadcrumb to current page
		$this->layout->breadcrumb = array(
		              array(
		    'title' => 'Inicio',
		    'link'  => '/',
		    'icon'  => 'fas fa-home'
		              ),
		              array(
		    'title' => 'Nuevo Origen',
		    'link'  => 'catalogos/origen',
		    'icon'  => 'fas fa-check'
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
		$origen = new Origen();

		if (!$origen->validate($new)) {
			$errors = $origen->errors();
			return Redirect::route('catalogos.origen.index')->withInput()->withErrors($errors);
		}

		$origen = Origen::create(Input::all());
		return Redirect::route('catalogos.origen.index')->with('success', 'Se ha agregado con éxito el nuevo Origen.');
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
		$this->layout           = View::make('catalogos.origen.create');
		$this->layout->title    = 'Editar Origen';
		$this->layout->origen		= Origen::find($id);

		// add breadcrumb to current page
		$this->layout->breadcrumb = array(
		              array(
		    'title' => 'Inicio',
		    'link'  => '/',
		    'icon'  => 'fas fa-home'
		              ),
		              array(
		    'title' => 'Editar Origen',
		    'link'  => 'catalogos/origen',
		    'icon'  => 'fas fa-check'
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
		$origen = Origen::find($id);
		if (!$origen->validate(Input::all(), $id)) {
				$errors = $origen->errors();
				return Redirect::route('catalogos.origen.edit', $id)->withInput()->withErrors($errors);
		}
		$origen->update(Input::all());
		return Redirect::route('catalogos.origen.edit', $id)->with('success', 'Se ha actualizado con éxito el Origen.');
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
