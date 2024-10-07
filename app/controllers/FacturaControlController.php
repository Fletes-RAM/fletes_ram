<?php

class FacturaControlController extends \BaseController {

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
		$factura = FacturaControl::find($id);
		$this->layout                = View::make('sistema.factura.edit_control');
		$this->layout->title         = 'Cobro de Factura de Control Vehicular '.$factura->factura;
		$this->layout->factura       = $factura;
		$this->layout->controles		 = ControlVehicular::where('factura_id',$id)->get();
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
		    'title' => 'Cobro de Factura de Control Vehicular '.$factura->factura,
		    'link'  => 'facturacontrol/edit',
		    'icon'  => 'fas fa-file-alt'
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
		//return dump(Input::all());
		$factura             = FacturaControl::find($id);
		$factura->fecha_pago = Input::get('fecha_pago', date('Y-m-d'));
		$factura->pagada     = 1;
		$periodo             = BancoPeriodo::find(1);
		$factura->observaciones = Input::get('observaciones', '');
		if ($factura->save()) {
			Input::merge([
				'periodo' => $periodo->periodo,
				'movimiento' => 'Pago Factura CV'. $factura->factura,
				'folio' => $factura->factura,
				'tipo' => 1,
				'cantidad' => $factura->total,
				'observaciones' => $factura->observaciones,
				'fecha' => Input::get('fecha_pago', date('Y-m-d')),
				'categoria_id' => Input::get('categoria_id'),
				'subcategoria_id' => Input::get('subcategoria_id')
			]);
			//return dump(Input::all());
			$banco = BancoMov::create(Input::all());
			return Redirect::route('factura.index')->with('success','Se ha guardado con éxito.');
		}
		return Redirect::back()->withInput()->with('error','¡Error! Intente de nuevo más tarde ');
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
