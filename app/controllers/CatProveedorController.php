<?php

class CatProveedorController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->layout        = View::make('sistema.cat_proveedor.index');
		$this->layout->title = 'Proveedores';


    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Proveedores',
				'link'  => 'cat_proveedor',
				'icon'  => 'fas fa-user-tie'
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
		$this->layout        = View::make('sistema.cat_proveedor.create');
		$this->layout->title = 'Proveedores';

    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Proveedores',
				'link'  => 'catalogos',
				'icon'  => 'fas fa-user-tie'
      ),
      array(
				'title' => 'Nuevo Proveedor',
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
		$new = Input::all();
		$proveedor = new CatProveedor();

		if(!$proveedor->validate($new)) {
			$errors = $proveedor->errors();
			return Redirect::back()->withInput()->withErrors($errors);
		}
		CatProveedor::create($new);
		return Redirect::route('cat_proveedor.index')->with('success','Se ha creado el nuevo proveedor con éxito.');
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
		$this->layout        = View::make('sistema.cat_proveedor.create');
		$this->layout->title = 'Proveedor';
		$this->layout->proveedor = CatProveedor::find($id);

    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Proveedor',
				'link'  => 'cat_proveedor',
				'icon'  => 'fas fa-user-tie'
      ),
      array(
				'title' => 'Editar Proveedor',
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
		$new = Input::all();
		$proveedor = CatProveedor::find($id);

		if(!$proveedor->validate($new)) {
			$errors = $proveedor->errors();
			return Redirect::back()->withInput()->withErrors($errors);
		}
		$proveedor->update($new);
		return Redirect::route('cat_proveedor.index')->with('success','Se ha actualizado el proveedor con éxito.');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		CatProveedor::destroy($id);
		return Redirect::route('cat_proveedor.index')->with('info','Se ha eliminado el proveedor con éxito.');
	}


}
