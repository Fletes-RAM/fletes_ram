<?php

class InventarioMaterialController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->layout           = View::make('sistema.inventariomaterial.index');
		$this->layout->title    = 'Inventario Materiales';
		$this->layout->materiales = InventarioMaterial::all();


    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Inventario Materiales',
				'link'  => 'material',
				'icon'  => 'fas fa-pallet'
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
		$this->layout           = View::make('sistema.inventariomaterial.create');
		$this->layout->title    = 'Inventario Materiales';


    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Inventario Materiales',
				'link'  => 'material',
				'icon'  => 'fas fa-pallet'
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
		Input::merge(['existencia'=>0,'valor'=>0]);
		$material = new InventarioMaterial();
		$new = Input::all();

		if (!$material->validate($new)) {
			$errors = $material->errors();
			return Redirect::route('material.create')->withInput()->withErrors($errors);
		}

		$material->create($new);

		return Redirect::route('material.index')->with('success','Se ha dado de alta el Inventario');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$this->layout           = View::make('sistema.inventariomaterial.show');
		$this->layout->title    = 'Inventario Materiales';
		$this->layout->material = InventarioMaterial::find($id);
		$this->layout->materialEntrada = InventarioMaterialEntrada::where('inventariomaterial_id',$id)->get();
		$this->layout->materialSalida = InventarioMaterialSalida::where('inventariomaterial_id',$id)->get();


    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Inventario Materiales',
				'link'  => 'material',
				'icon'  => 'fas fa-pallet'
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
