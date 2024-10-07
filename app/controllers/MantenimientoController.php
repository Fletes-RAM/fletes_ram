<?php

class MantenimientoController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// $mantenimientos = Mantenimiento::where('status','')->with('unidad')->get();
		// foreach ($mantenimientos as $mantenimiento) {
		// 	dump($mantenimiento->factura);
		// 	dump($mantenimiento->unidad->unidad);
		// 	echo "-------------------<br>";
		// }
		// return false;
		$this->layout                 = View::make('sistema.mantenimiento.index');
		$this->layout->title          = 'Mantenimiento de Unidades';
		$this->layout->mantenimientos = Mantenimiento::where('status','')->with('unidad')->get();
		$this->layout->breadcrumb     = array(
      array(
        'title' => 'Inicio',
        'link'  => '/',
        'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Mantenimiento de Unidades',
				'link'  => 'mantenimiento',
				'icon'  => 'fas fa-toolbox'
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
		$this->layout                 = View::make('sistema.mantenimiento.create');
		$this->layout->title          = 'Mantenimiento de Unidades';
		$this->layout->unidades_list  = DB::table('unidades_list')->orderBy('unidad')->lists('unidad', 'id');
		$this->layout->proveedores    = CatProveedor::orderBy('proveedor')->lists('proveedor','id');
		$this->layout->breadcrumb     = array(
      array(
        'title' => 'Inicio',
        'link'  => '/',
        'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Mantenimiento de Unidades',
				'link'  => 'mantenimiento',
				'icon'  => 'fas fa-toolbox'
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
		$mantenimiento = new Mantenimiento();
        
		if(!$mantenimiento->validate($new)) {
            $errors = $mantenimiento->errors();
			return Redirect::back()->withInput()->withErrors($errors);
		}
		Mantenimiento::create($new);
        //return dump($new);
		return Redirect::route('mantenimiento.index')->with('success','Se ha creado la Factura de mantenimiento con éxito.');
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
		$mantenimientos = array();
		foreach (Input::get('comprobante_id') as $key => $value) {
			$mantenimientos[] = Mantenimiento::find($value);
		}

		$this->layout                 = View::make('sistema.mantenimiento.edit');
		$this->layout->title          = 'Relación de Notas de Mantenimiento';
		$this->layout->mantenimientos = $mantenimientos;
		$this->layout->bancos         = DB::table('bancos_list')->lists('banco', 'id');
		$this->layout->categorias     = BancoCategoria::lists('categoria', 'id');
		$this->layout->subcategorias  = BancoSubCategoria::lists('subcategoria', 'id');
		$this->layout->proveedores    = CatProveedor::lists('proveedor','id');

		// add breadcrumb to current page
		$this->layout->breadcrumb = array(
		              array(
		    'title' => 'Inicio',
		    'link'  => '/',
		    'icon'  => 'fas fa-home'
		              ),
		              array(
		    'title' => 'Relación de Notas de Mantenimiento',
		    'link'  => 'mantenimiento',
		    'icon'  => 'fas fa-toolbox'
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
		$periodo       = BancoPeriodo::find(1);
		$observaciones = "Pago de tickets de mantenimiento: ";
		foreach (Input::get('id') as $key => $value) {
			list($tipo,$i) = explode('-',$value);
			$tipo = $tipo;
			$i = $i;
			if ($tipo == 'a') {
				$mantenimiento = Mantenimiento::find($i);
			}
			
		//return dump(Input::get('proveedor_id'));
			$mantenimiento->status = 'Pagado';
			$mantenimiento->proveedor_id = Input::get('proveedor_id');
			$mantenimiento->save();
			$observaciones = $observaciones . $mantenimiento->factura . " ";
		}
		Input::merge([
			'bancos_id' => Input::get('banco_id'),
			'periodo' => $periodo->periodo,
			'movimiento' => 'Pago Factura '. Input::get('factura'),
			'folio' => Input::get('factura'),
			'tipo' => -1,
			'cantidad' => Input::get('valor_factura'),
			'observaciones' => $observaciones
		]);
		$banco = BancoMov::create(Input::all());
		return Redirect::route('mantenimiento.index')->with('success','Se ha guardado con éxito.');
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
