<?php

class ClienteController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->layout        = View::make('sistema.cliente.index');
		$this->layout->title = 'Clientes';


    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Clientes',
				'link'  => 'cliente',
				'icon'  => 'fas fa-address-book'
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
		$this->layout        = View::make('sistema.cliente.create');
		$this->layout->title = 'Clientes';

    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Clientes',
				'link'  => 'catalogos',
				'icon'  => 'fas fa-address-book'
      ),
      array(
				'title' => 'Nuevo Cliente',
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
		$cliente = new Cliente();
		if (!$cliente->validate($new)) {
			$errors = $cliente->errors();
			return Redirect::route('newCliente')->withInput()->withErrors($errors);
		}

		$cliente->cliente         = Input::get('cliente');
		$cliente->nombre_contacto = Input::get('nombre_contacto');
		$cliente->email           = Input::get('email');
		$cliente->telefono        = Input::get('telefono');
		$cliente->gasto_admon     = Input::get('gasto_admon');
		$cliente->observaciones   = Input::get('observaciones');
		if ($cliente->save()) {
			$subcat = new BancoSubCategoria();
			$subcat->categoria_id = 2;
			$subcat->subcategoria = Input::get('cliente');
			$subcat->save();
			return Redirect::route('listCliente')->with('success','Se ha agregado con éxito al nuevo Cliente.');
		}
		return Redirect::route('newCliente')->withInput()->withErrors($errors);
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
		$this->layout        = View::make('sistema.cliente.create');
		$this->layout->title = 'Cliente';
		$this->layout->cliente = Cliente::find($id);

    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Cliente',
				'link'  => 'cliente',
				'icon'  => 'fas fa-address-book'
      ),
      array(
				'title' => 'Editar Cliente',
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
		$cliente = Cliente::find($id);
		if (!$cliente->validate(Input::all())) {
			$errors = $cliente->errors();
			return Redirect::route('putCliente',$id)->withInput()->withErrors($errors);
		}

		$cliente->cliente    = Input::get('cliente');
		$cliente->nombre_contacto      = Input::get('nombre_contacto');
		$cliente->email         = Input::get('email');
		$cliente->telefono      = Input::get('telefono');
		$cliente->gasto_admon     = Input::get('gasto_admon');
		$cliente->observaciones = Input::get('observaciones');
		if ($cliente->save()) {
			return Redirect::route('listCliente')->with('success','Se han guardado con éxito los cambios	.');
		}
		return Redirect::route('putCliente',$id)->withInput()->withErrors($errors);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$cliente = Cliente::destroy($id);
		return Redirect::route('listCliente')->with('info','Se ha eliminado al Cliente');
	}


}
