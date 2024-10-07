<?php

class DieselAutorizadoController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->layout                   = View::make('sistema.dieselautorizado.index');
		$this->layout->title            = 'Diesel Autorizado';
		$this->layout->dieselautorizado = DieselAutorizado::all();
		$this->layout->breadcrumb       = array(
            array(
                'title' => 'Inicio',
                'link'  => '/',
                'icon'  => 'fas fa-home'
            ),
            array(
                'title' => 'Diesel Autorizado',
                'link'  => 'mantenimiento',
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
		$this->layout                   = View      ::make('sistema.dieselautorizado.create');
        $this->layout->title            = 'Diesel Autorizado';
        $this->layout->tipo_unidad_list = TipoUnidad::lists('tipo_de_unidad', 'id');

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
            array(
                        'title' => 'Inicio',
                        'link'  => '/',
                        'icon'  => 'fas fa-home'
            ),
            array(
                        'title' => 'Diesel Autorizado',
                        'link'  => 'dieselAutorizado/create',
                        'icon'  => 'fas fa-gas-pump'
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
		$diesel = new DieselAutorizado();
		if (!$diesel->validate($new)) {
			$errors = $diesel->errors();
			return Redirect::route('dieselAutorizado.create')->withInput()->withErrors($errors);
		}

        $diesel->create($new);
		return Redirect::route('dieselAutorizado.index')->with('success', 'Se ha creado con éxito el Diesel Autorizado');
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
		DieselAutorizado::destroy($id);
        return Redirect::route('dieselAutorizado.index')->with('info', 'Se ha eliminado con éxito el Diesel Autorizado');
	}


}
