<?php

class InventarioLlantaController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->layout           = View::make('sistema.llanta.index');
		$this->layout->title    = 'Inventario Llantas';
		$this->layout->llantas  = Llanta::all();

    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Inventario Llantas',
				'link'  => 'llantas ',
				'icon'  => 'fas fa-truck-monster'
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
		$this->layout           = View::make('sistema.llanta.create');
		$this->layout->title    = 'Inventario Llantas';


    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Inventario Llantas',
				'link'  => 'llanta',
				'icon'  => 'fas fa-truck-monster'
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
		Input::merge(['existencia'=>0]);
		$llanta = new Llanta();
		$new = Input::all();

		if (!$llanta->validate($new)) {
			$errors = $llanta->errors();
			return Redirect::route('llanta.create')->withInput()->withErrors($errors);
		}

		$llanta->create($new);

		return Redirect::route('llanta.index')->with('success','Se ha dado de alta el Inventario');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$this->layout           = View::make('sistema.llanta.show');
		$this->layout->title    = 'Inventario Llantas';
		$this->layout->llanta = Llanta::find($id);
		$this->layout->llantaEntrada = LlantaEntrada::where('llanta_id',$id)->get();
		$this->layout->llantaSalida = LlantaSalida::where('llanta_id',$id)->get();


    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Inventario Llantas',
				'link'  => 'llanta',
				'icon'  => 'fas fa-truck-monster'
      ),
    );
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
