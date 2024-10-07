<?php

class LlantaSalidaController extends \BaseController {

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
		$this->layout           = View::make('sistema.llanta.createSalida');
		$this->layout->title    = 'Inventario Llantas Salida';
		$this->layout->id = Input::get('id');
		$this->layout->llanta = Llanta::find($this->layout->id);
		$this->layout->unidades_list = DB::table('unidades_list')->orderBy('unidad')->lists('unidad', 'id');


    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Inventario Llantas Salida',
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
		$new = Input::all();
		$id = Input::get('llanta_id');
		// return dump($id);

		$llantaSalida = new LlantaSalida();

		if (!$llantaSalida->validate($new)) {
			$errors = $llantaSalida->errors();
			return Redirect::route('llantaSalida.create','id='.$id)->withInput()->withErrors($errors);
		}

		$llantaSalida->create($new);

		$llanta = Llanta::find($id);

		$llanta->existencia = $llanta->existencia - Input::get('cantidad');
		$llanta->save();

		return Redirect::route('llanta.show',$id)->with('success','Se ha agregado la entrada con éxito');
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
