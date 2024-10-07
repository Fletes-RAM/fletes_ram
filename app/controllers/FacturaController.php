<?php

class FacturaController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->layout                     = View::make('sistema.factura.index');
		$this->layout->title              = 'Facturación';
		$this->layout->cotizaciones       = Cotizacion::whereRaw('id not in (select cotizacion_id from ram_facturas) AND id IN (SELECT cotizacion_id from ram_asignaciones)')->orderBy('created_at','desc')->get();
		$this->layout->facturas           = Factura::where('pagada',0)->get();
		$this->layout->facturas_controles = FacturaControl::where('pagada',0)->get();
		$this->layout->controles          = ControlVehicular::whereRaw('id not in (select control_id from ram_facturas_controles) and pagado = ""')->whereNull('factura_id')->orderBy('created_at','desc')->get();

		// add breadcrumb to current page
		$this->layout->breadcrumb = array(
		              array(
		    'title' => 'Inicio',
		    'link'  => '/',
		    'icon'  => 'fas fa-home'
		              ),
		              array(
		    'title' => 'Facturación',
		    'link'  => 'factura',
		    'icon'  => 'fas fa-file-alt'
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
		$this->layout           = View::make('sistema.factura.create');
		$this->layout->title    = 'Asignar Factura';
		$this->layout->cotizacion	= Cotizacion::find(Input::get('id'));
		$this->layout->asignacion = Asignacion::where('cotizacion_id',Input::get('id'))->first();

		// add breadcrumb to current page
		$this->layout->breadcrumb = array(
		              array(
		    'title' => 'Inicio',
		    'link'  => '/',
		    'icon'  => 'fas fa-home'
		              ),
		              array(
		    'title' => 'Asignar Factura',
		    'link'  => 'factura/create',
		    'icon'  => 'fas fa-file-alt'
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
		$factura = new Factura();
		if(!$factura->validate($new)) {
			$errors = $factura->errors();
			return Redirect::back()->withInput()->withErrors($errors);
		}
		Factura::create($new);
		return Redirect::route('factura.index')->with('success','Se ha creado la Factura con éxito.');
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
		$factura = Factura::find($id);
		$this->layout                = View::make('sistema.factura.edit');
		$this->layout->title         = 'Cobro de Factura '.$factura->factura;
		$this->layout->factura       = $factura;
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
		    'title' => 'Cobro de Factura '.$factura->factura,
		    'link'  => 'factura/edit',
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
		$factura             = Factura::find($id);
		$factura->fecha_pago = Input::get('fecha', date('Y-m-d'));
		$factura->pagada     = 1;
		$periodo             = BancoPeriodo::find(1);
		$factura->observaciones = Input::get('observaciones', '');
		if ($factura->save()) {
			Input::merge([
				'periodo' => $periodo->periodo,
				'movimiento' => 'Pago Factura '. $factura->factura,
				'folio' => $factura->factura,
				'tipo' => 1,
				'cantidad' => $factura->total,
				'observaciones' => $factura->observaciones,
				'fecha' => Input::get('fecha', date('Y-m-d')),
				'categoria_id' => Input::get('categoria_id'),
				'subcategoria_id' => Input::get('subcategoria_id')
			]);
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
