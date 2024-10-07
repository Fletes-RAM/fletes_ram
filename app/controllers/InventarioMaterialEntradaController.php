<?php

class InventarioMaterialEntradaController extends \BaseController {

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
		$this->layout           = View::make('sistema.inventariomaterial.createEntrada');
		$this->layout->title    = 'Inventario Materiales Entrada';
		$this->layout->id = Input::get('id');
		$this->layout->material = InventarioMaterial::find($this->layout->id);


    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Inventario Materiales Entrada',
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
		$new = Input::all();
		$id = Input::get('inventariomaterial_id');

		$materialEntrada = new InventarioMaterialEntrada();

		if (!$materialEntrada->validate($new)) {
			$errors = $materialEntrada->errors();
			return Redirect::route('materialEntrada.create')->withInput()->withErrors($errors);
		}

		$materialEntrada->create($new);

		$material = InventarioMaterial::find($id);

		$material->precio = Input::get('precio');
		$material->existencia = $material->existencia + Input::get('cantidad');
		$material->valor = $material->existencia * $material->precio;
		$material->save();

		return Redirect::route('material.show',$id)->with('success','Se ha agregado la entrada con éxito');
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
