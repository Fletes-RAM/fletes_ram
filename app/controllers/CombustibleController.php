<?php

class CombustibleController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
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
		//
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
		$this->layout           = View::make('sistema.combustible.edit');
		$this->layout->title    = 'Editar Ticket de Combustible';

		// add breadcrumb to current page
		$this->layout->breadcrumb = array(
		              array(
		    'title' => 'Inicio',
		    'link'  => '/',
		    'icon'  => 'fas fa-home'
		              ),
		              array(
		    'title' => 'Editar Ticket de Combustible',
		    'link'  => 'combustible/'.$id.'/edit',
		    'icon'  => 'fas fa-file-invoice-dollar'
		              ),
		            );
		list($tipo,$i) = explode('-',$id);
		$this->layout->tipo = $tipo;
		$this->layout->i = $i;
		if ($tipo == 'a') {
			$this->layout->ticket = AsignacionCombustible::find($i);
		}
		if ($tipo == 'e') {
			$this->layout->ticket = AsignacionEspecial::find($i);
		}
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//return dump(Input::all());
		if (Input::get('tipo') == 'a') {
			$asignacion = AsignacionCombustible::find($id);
		}elseif(Input::get('tipo') == 'e') {
			$asignacion = AsignacionEspecial::find($id);
		}
		$total = Input::get('litros') * Input::get('precio');
		Input::merge(['total'=>$total]);
		$asignacion->update(Input::all());
		return Redirect::route('showCombustibles', ['fecha1'=>Input::get('fecha1'),'fecha2'=>Input::get('fecha2')])->with('success', 'Se ha actualizado con éxito el ticket!');
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
