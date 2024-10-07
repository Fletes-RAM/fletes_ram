<?php

class ProveedorController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->layout               = View::make('sistema.proveedor.index');
		$this->layout->title        = 'Pendientes por Pagar';
		$this->layout->comprobantes = ComprobanteProveedorVista::where('user_id','!=','null')->get();
		// add breadcrumb to current page
		$this->layout->breadcrumb = array(
		              array(
		    'title' => 'Inicio',
		    'link'  => '/',
		    'icon'  => 'fas fa-home'
		              ),
		              array(
		    'title' => 'Pendientes por Pagar',
		    'link'  => '/proveedor',
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
		$gas = 'inicio';
		$ticketa = array();
		$tickete = array();
		foreach (Input::get('comprobante_id') as $key => $value) {
			list($tipo,$i) = explode('-',$value);
			$tipo = $tipo;
			$i = $i;
			if ($tipo == 'a') {
				$ticketa[] = AsignacionCombustible::find($i);
				$ticket = AsignacionCombustible::find($i);
			}
			if ($tipo == 'e') {
				$tickete[] = AsignacionEspecial::find($i);
				$ticket = AsignacionEspecial::find($i);
			}
			if ($gas != 'inicio') {
				if ($gas != $ticket->gasolinera_id) {
					return Redirect::back()->with('error','Los tickets no pertenecen a la misma estacion');
				}
			}
			$gas = $ticket->gasolinera_id;
		}

		$this->layout                = View::make('sistema.proveedor.create');
		$this->layout->title         = 'Relación de Notas';
		$this->layout->ticketa       = $ticketa;
		$this->layout->tickete       = $tickete;
		$this->layout->bancos        = DB::table('bancos_list')->lists('banco', 'id');
		$this->layout->categorias    = BancoCategoria::lists('categoria', 'id');
		$this->layout->subcategorias = BancoSubCategoria::lists('subcategoria', 'id');

		// add breadcrumb to current page
		$this->layout->breadcrumb = array(
		              array(
		    'title' => 'Inicio',
		    'link'  => '/',
		    'icon'  => 'fas fa-home'
		              ),
		              array(
		    'title' => 'Relación de Notas',
		    'link'  => 'proveedor/create',
		    'icon'  => 'fas fa-user-tie'
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
		$periodo       = BancoPeriodo::find(1);
		$observaciones = "Pago de tickets: ";
		$gasolinera    = '';
		foreach (Input::get('id') as $key => $value) {
			list($tipo,$i) = explode('-',$value);
			$tipo = $tipo;
			$i = $i;
			if ($tipo == 'a') {
				$ticket = AsignacionCombustible::find($i);
			}
			if ($tipo == 'e') {
				$ticket = AsignacionEspecial::find($i);
			}
			Input::merge(['comprobante_id'=>$value]);
			$new = Input::all();
			$proveedor = new Proveedor();
			if (!$proveedor->validate($new)) {
				$errors = $proveedor->errors();
				return Redirect::back()->withInput()->withErrors($errors);
			}
			$observaciones = $observaciones . $ticket->ticket . " ";
			if ($ticket->gasolinera_id != 0){
				$gasolinera = $ticket->gasolinera->gasolinera;
			}else{
				$gasolinera = "Otra";
			}
			$banco = Proveedor::create(Input::all());
		}
		Input::merge([
			'bancos_id' => Input::get('banco_id'),
			'periodo' => $periodo->periodo,
			'movimiento' => 'Pago Factura '. $gasolinera,
			'folio' => Input::get('factura'),
			'tipo' => -1,
			'cantidad' => Input::get('valor_factura'),
			'observaciones' => $observaciones
		]);
		$banco = BancoMov::create(Input::all());
		return Redirect::route('proveedor.index')->with('success','Se ha guardado con éxito.');
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
