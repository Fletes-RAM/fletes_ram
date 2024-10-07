<?php

class RendimientoController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->layout        = View::make('catalogos.rendimiento.index');
		$this->layout->title = 'Tipo de Unidad';
		

    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Rendimiento de Combustible',
				'link'  => 'catalogos/rendimiento',
				'icon'  => 'fas fa-thermometer-half'
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
		$this->layout                  = View::make('catalogos.rendimiento.create');
		$this->layout->title           = 'Rendimiento de Combustible';
		$this->layout->tipounidad_list = TipoUnidad::lists('tipo_de_unidad','id');

    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Rendimiento de Combustible',
				'link'  => 'catalogos/rendimiento',
				'icon'  => 'fas fa-thermometer-half'
      ),
      array(
				'title' => 'Rendimiento de Combustible Nuevo',
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
		$rendimiento = new Rendimiento();
		if (!$rendimiento->validate($new)) {
			$errors = $rendimiento->errors();
			return Redirect::route('newRendimiento')->withInput()->withErrors($errors);
		}

		$rendimiento->tipo_de_unidad_id = Input::get('tipo_de_unidad_id');
		$rendimiento->rendimiento       = Input::get('rendimiento');
		if ($rendimiento->save()) {
			return Redirect::route('listRendimiento')->with('success','Se ha agregado con éxito el nuevo Rendimiento de Combustible.');
		}
		return Redirect::route('newRendimiento')->withInput()->withErrors($errors);
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
		$this->layout                  = View::make('catalogos.rendimiento.create');
		$this->layout->title           = 'Rendimiento de Combustible';
		$this->layout->tipounidad_list = TipoUnidad::lists('tipo_de_unidad','id');
		$this->layout->rendimiento     = Rendimiento::find($id);

    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Rendimiento de Combustible',
				'link'  => 'catalogos/rendimiento',
				'icon'  => 'fas fa-thermometer-half'
      ),
      array(
				'title' => 'Editar Rendimiento de Combustible',
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
		$rendimiento = Rendimiento::find($id);
		if (!$rendimiento->validate(Input::all())) {
			$errors = $rendimiento->errors();
			return Redirect::route('putRendimiento',$id)->withInput()->withErrors($errors);
		}

		$rendimiento->tipo_de_unidad_id = Input::get('tipo_de_unidad_id');
		$rendimiento->rendimiento       = Input::get('rendimiento');
		if ($rendimiento->save()) {
			return Redirect::route('listRendimiento')->with('success','Se han guardado con éxito los cambios	.');
		}
		return Redirect::route('putRendimiento',$id)->withInput()->withErrors($errors);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$rendimiento = Rendimiento::destroy($id);
		return Redirect::route('listRendimiento')->with('info','Se ha eliminado el Rendimiento de Combustible');
	}


}
