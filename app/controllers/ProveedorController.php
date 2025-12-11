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

	public function postAsignarFactura()
	{
		$idsStr           = Input::get('comprobantes_ids');
		$facturaProveedor = Input::get('factura_proveedor');
		$fechaLimitePago  = Input::get('fecha_limite_pago');

		if (empty($idsStr)) {
			return Redirect::back()->with('error', 'No se recibieron comprobantes seleccionados.');
		}

		// Ejemplo: "a-10,e-17920,e-18844"
		$ids = array_filter(explode(',', $idsStr));

		if (empty($ids)) {
			return Redirect::back()->with('error', 'No se seleccionó ningún comprobante válido.');
		}

		// Validar que todos los tickets pertenezcan a la misma gasolinera
		$gasolineraBase = null;

		foreach ($ids as $valor) {

			if (strpos($valor, '-') === false) {
				continue;
			}

			list($tipo, $id) = explode('-', $valor);

			if ($tipo === 'a') {
				$ticket = AsignacionCombustible::find($id);
			} elseif ($tipo === 'e') {
				$ticket = AsignacionEspecial::find($id);
			} else {
				continue;
			}

			if (!$ticket) continue;

			if (!$gasolineraBase) {
				// primera gasolinera encontrada
				$gasolineraBase = $ticket->gasolinera_id;
			} else {
				// validar
				if ($gasolineraBase != $ticket->gasolinera_id) {
					return Redirect::back()
						->with('error', 'Los tickets seleccionados no pertenecen a la misma gasolinera. No se pueden facturar juntos.');
				}
			}
		}

		// Validaciones básicas
		$rules = [
			'factura_proveedor' => 'required',
			'fecha_limite_pago' => 'required|date',
		];

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			return Redirect::back()
				->withErrors($validator)
				->withInput();
		}

		try {
			DB::beginTransaction();

			foreach ($ids as $valor) {
				// valor = "a-10" o "e-17920"
				if (strpos($valor, '-') === false) {
					// Si algo viene raro, lo brincamos
					continue;
				}

				list($tipo, $id) = explode('-', $valor);

				$ticket = null;

				if ($tipo === 'a') {
					// Ticket de AsignacionCombustible
					$ticket = AsignacionCombustible::find($id);
				} elseif ($tipo === 'e') {
					// Ticket de AsignacionEspecial
					$ticket = AsignacionEspecial::find($id);
				}

				if (!$ticket) {
					continue;
				}

				// Asegúrate de que estos campos existan en las tablas correspondientes:
				//  - factura_proveedor (VARCHAR)
				//  - dias_credito (INT)
				$ticket->factura_proveedor = $facturaProveedor;
				$ticket->fecha_limite_pago = $fechaLimitePago;
				$ticket->save();
			}

			DB::commit();

			return Redirect::route('proveedor.index')
				->with('success', 'Se asignó la factura y los días de crédito a los comprobantes seleccionados.');
		} catch (\Exception $e) {
			DB::rollBack();
			// Log::error($e);
			return Redirect::back()->with('error', 'Ocurrió un error al asignar la factura. Inténtalo de nuevo.');
		}
	}




}
