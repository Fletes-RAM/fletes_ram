<?php

class CodigoController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->layout        = View::make('catalogos.codigo.index');
		$this->layout->title = 'Municipios';
		$this->layout->estados = Estado::lists('estado','idEstado');
		

    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Municipios',
				'link'  => 'municipio',
				'icon'  => 'fas fa-map'
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
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$estado = Estado::where('idEstado',Input::get('estado'))->first();

		$codigo = new Codigo();
		$codigo->idEstado = Input::get('estado');
		$codigo->estado = $estado->estado;
		$codigo->municipio = Input::get('municipio');
		$codigo->cp = '00000';

		if ($codigo->save()) {
			return Redirect::route('listCodigo')->with('success','Se ha agregado con éxito el nuevo Municipio.');
		}
		return Redirect::route('listCodigo')->withInput()->withErrors($errors);
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
