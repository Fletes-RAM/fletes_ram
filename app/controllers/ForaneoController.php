<?php

class ForaneoController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
		$this->layout           = View::make('sistema.foraneo.index');
		$this->layout->title    = 'Foraneos';
		$this->layout->foraneos = DB::table('foraneo_operador_view')->get();


    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Foraneos',
				'link'  => 'foraneo',
				'icon'  => 'fas fa-id-card'
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
		$this->layout                = View::make('sistema.foraneo.create');
		$this->layout->title         = 'Movimientos Foráneos';
		$this->layout->operadores    = ForaneoOperador::lists('foraneo_operador', 'id');
		$this->layout->unidades_list = DB::table('unidades_list')->orderBy('unidad')->lists('unidad', 'id');
		$this->layout->bancos        = DB::table('bancos_list')->lists('banco', 'id');

    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Movimientos Foráneos',
				'link'  => 'foraneo',
				'icon'  => 'fas fa-users'
      ),
      array(
				'title' => 'Nuevo Movimiento Foráneo',
				'link'  => '#',
				'icon'  => 'fas fa-id-card'
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

		//return dump(Input::all());
		$operador = Foraneo::where('foraneo_operador_id',Input::get('foraneo_operador_id'))->orderBy('id', 'DESC')->first();

		if (!isset($operador)) {
			
			Input::merge(['saldo'=>Input::get('monto')]);
			Input::merge(['tp'=>1]);
			$new = Input::all();

			//return dump(Input::all());
			$foraneo = new Foraneo();

			if(!$foraneo->validate($new)) {
				$errors = $foraneo->errors();
				return Redirect::back()->withInput()->withErrors($errors);
			}

			$foraneo = Foraneo::create(Input::all());
			return Redirect::route('foraneo.index')->with('success','Se ha creado el movimiento con éxito');
		}

		switch (Input::get('tipo')) {
			case 'Pago de Fletes':
				Input::merge(['tp'=>1]);
				break;

			case 'Transferencia':
				Input::merge(['tp'=>-1]);
				break;

			case 'Pago a Proveedores':
				Input::merge(['tp'=>-1]);
				break;

			case 'Aportaciones':
				Input::merge(['tp'=>1]);
				break;

			case 'Retiros':
				Input::merge(['tp'=>-1]);
				break;

			case 'Otros':
				Input::merge(['tp'=>1]);
				break;
		}

		$monto = Input::get('monto') * Input::get('tp');
		$saldo = $operador->saldo + $monto;

		Input::merge(['saldo'=>$saldo]);

		$foraneo = Foraneo::create(Input::all());


		$periodo             = BancoPeriodo::find(1);

		if (isset($foraneo)) {
			Input::merge([
				'periodo' => $periodo->periodo,
				'movimiento' => 'Movimiento Operadores Foraneos id: '. $foraneo->id,
				'folio' => 'MF-'.$foraneo->id,
				'tipo' => $foraneo->tp,
				'cantidad' => Input::get('monto'),
				'observaciones' => 'N/A',
				'fecha' => Input::get('fecha', date('Y-m-d')),
				'categoria_id' => 13,
				'subcategoria_id' => 93
			]);
			//return dump(Input::all());
			$banco = BancoMov::create(Input::all());
			return Redirect::route('foraneo.index')->with('success','Se ha creado el movimiento con éxito');
		}

		return Redirect::back()->withInput()->with('error','¡Error! Intente de nuevo más tarde ');

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
