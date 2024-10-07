<?php

class ReporteController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->layout             = View::make('sistema.reporte.index');
        $this->layout->title      = 'Reportes';
        $this->layout->operadores = DB::table('operadores_list')->lists('nombre', 'id');
        $admins = Sentry::findGroupByName('AdminsSueldos');
        $adminis = Sentry::findAllUsersInGroup($admins);
        $add = [];
        foreach ($adminis as $admin) {
            $add[$admin->id] = $admin->first_name . ' ' . $admin->last_name;
        }
        $this->layout->administradores = $add;
        $this->layout->unidades_list = DB::table('unidades_list')->orderBy('unidad')->lists('unidad', 'id');
        $this->layout->bancos     = DB::table('bancos_list')->where('id','!=',7)->lists('banco', 'id');
        $this->layout->periodo    = DB::table('bancos_detalle')
                                       ->select('periodo')
                                       ->orderBy('anno','desc')
                                       ->orderByRaw("FIELD(mes , 'DICIEMBRE', 'NOVIEMBRE', 'OCTUBRE', 'SEPTIEMBRE', 'AGOSTO', 'JULIO', 'JUNIO', 'MAYO', 'ABRIL', 'MARZO', 'FEBRERO', 'ENERO') ASC")
                                       ->groupBy('periodo')
                                       ->lists('periodo', 'periodo');
        $this->layout->anno    = DB::table('bancos_detalle')
                                       ->select('anno')
                                       ->orderBy('anno')
                                      // ->orderByRaw("FIELD(mes , 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE') ASC")
                                       ->groupBy('anno')
                                       ->lists('anno', 'anno');
        $this->layout->breadcrumb = array(
      array(
                'title' => 'Inicio',
                'link'  => '/',
                'icon'  => 'fas fa-home'
      ),
      array(
                'title' => 'Reportes',
                'link'  => 'reporte',
                'icon'  => 'fas fa-chart-bar'
      ),
  );
    }

    public function reporte()
    {
        $periodo                    = Input::get('periodo');
        $banco = Input::get('bancos_id');
        $this->layout               = View::make('sistema.reporte.presupuesto');
        $this->layout->title        = 'Reportes';
        $this->layout->presupuestos = BancoPresupuesto::where('periodo', $periodo)->where('bancos_id', $banco)
                                                        ->orderBy('anno')
                                                        ->orderByRaw("FIELD(mes , 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE') ASC")->get();
        $this->layout->ban          = Banco::find(Input::get('bancos_id'));
        $this->layout->breadcrumb   = array(
          array(
            'title' => 'Inicio',
            'link'  => '/',
            'icon'  => 'fas fa-home'
          ),
          array(
            'title' => 'Reportes',
            'link'  => 'reporte',
            'icon'  => 'fas fa-chart-bar'
          ),
        );
    }

    public function presupuesto()
    {
        $this->layout              = View::make('sistema.reporte.budget');
        $this->layout->title       = 'Presupuesto General';
        $this->layout->breadcrumb  = array(
        array(
          'title' => 'Inicio',
          'link'  => '/',
          'icon'  => 'fas fa-home'
        ),
        array(
          'title'     => 'Presupuesto.',
          'link'      => 'presupuesto',
          'icon'      => 'fas fa-wallet'
            ),
      );
        $this->layout->periodo = Input::get('periodo', 'Junio-2018');

        /*Categoría Saldo Inicial*/
        $this->layout->categoriasi = BancoReportePresupuesto::select(DB::raw('categoria_id, sum(total) as total'))
                                    ->where('periodo', $this->layout->periodo)
                                    ->where('categoria_id', 0)
                                    ->where('subcategoria_id', 0)
                                    ->where('bancos_id', '!=', 6)
                                    ->where('bancos_id', '!=', 7)
                                    ->where('bancos_id', '!=', 9)
                                    ->groupBy('categoria_id')->get();
        $this->layout->bancosi = BancoReportePresupuesto::select(DB::raw('bancos_id, sum(total) as total'))
                                    ->where('periodo', $this->layout->periodo)
                                    ->where('categoria_id', 0)
                                    ->where('subcategoria_id', 0)
                                    ->where('bancos_id', '!=', 6)
                                    ->where('bancos_id', '!=', 7)
                                    ->where('bancos_id', '!=', 9)
                                    ->groupBy('bancos_id')->get();
        /*Categoría Saldo Inicial 2*/
        $this->layout->categoriasi2 = BancoReportePresupuesto::select(DB::raw('categoria_id, sum(total) as total'))
                                    ->where('periodo', $this->layout->periodo)
                                    ->where('categoria_id', 4)
                                    ->where('bancos_id', '!=', 6)
                                    ->where('bancos_id', '!=', 7)
                                    ->where('bancos_id', '!=', 9)
                                    ->groupBy('categoria_id')->get();
        $this->layout->bancosi2 = BancoReportePresupuesto::select(DB::raw('bancos_id, sum(total) as total'))
                                    ->where('periodo', $this->layout->periodo)
                                    ->where('categoria_id', 4)
                                    ->where('bancos_id', '!=', 6)
                                    ->where('bancos_id', '!=', 7)
                                    ->where('bancos_id', '!=', 9)
                                    ->groupBy('bancos_id')->get();
        /*Categorías Positivas*/
        $this->layout->categoriasp = BancoReportePresupuesto::select(DB::raw('categoria_id, sum(total) as total'))
                                    ->where('periodo', $this->layout->periodo)
                                    ->where('total', '>=', 0)
                                    ->where('categoria_id', '!=', 0)
                                    ->where('categoria_id', '!=', 4)
                                    ->where('bancos_id', '!=', 6)
                                    ->where('bancos_id', '!=', 7)
                                    ->where('bancos_id', '!=', 9)
                                    ->groupBy('categoria_id')
                                    ->orderBy('categoria_id')->get();
        /*Categorías Negativas*/
        $this->layout->categoriasn = BancoReportePresupuesto::select(DB::raw('categoria_id, sum(total) as total'))
                                    ->where('periodo', $this->layout->periodo)
                                    ->where('total', '<', 0)
                                    ->where('categoria_id', '!=', 0)
                                    ->where('categoria_id', '!=', 4)
                                    ->where('bancos_id', '!=', 6)
                                    ->where('bancos_id', '!=', 7)
                                    ->where('bancos_id', '!=', 9)
                                    ->groupBy('categoria_id')
                                    ->orderBy('categoria_id')->get();
        /*Categoría Saldo Inicial*/
        $this->layout->categoriasf = BancoReportePresupuesto::select(DB::raw('categoria_id, sum(total) as total'))
                                    ->where('periodo', $this->layout->periodo)
                                    ->where('categoria_id', 0)
                                    ->where('subcategoria_id', 1)
                                    ->where('bancos_id', '!=', 6)
                                    ->where('bancos_id', '!=', 7)
                                    ->where('bancos_id', '!=', 9)
                                    ->groupBy('categoria_id')->get();
        $this->layout->bancosf = BancoReportePresupuesto::select(DB::raw('bancos_id, sum(total) as total'))
                                    ->where('periodo', $this->layout->periodo)
                                    ->where('categoria_id', 0)
                                    ->where('bancos_id', '!=', 6)
                                    ->where('bancos_id', '!=', 7)
                                    ->where('bancos_id', '!=', 9)
                                    ->where('subcategoria_id', 1)
                                    ->groupBy('bancos_id')->get();
    }

    public function presupuestoYear()
    {
        $this->layout              = View::make('sistema.reporte.budget_year');
        $this->layout->title       = 'Presupuesto General';
        $this->layout->breadcrumb  = array(
        array(
          'title' => 'Inicio',
          'link'  => '/',
          'icon'  => 'fas fa-home'
        ),
        array(
          'title'     => 'Presupuesto.',
          'link'      => 'presupuesto',
          'icon'      => 'fas fa-wallet'
            ),
      );
        $this->layout->periodo = Input::get('periodo', '2020');

        /*Categoría Saldo Inicial*/
        $this->layout->categoriasi = BancoReportePresupuesto::select(DB::raw('categoria_id, sum(total) as total'))
                                    ->where('year', $this->layout->periodo)
                                    ->where('categoria_id', 0)
                                    ->where('subcategoria_id', 0)
                                    ->where('mes', 'Enero')
                                    ->where('bancos_id', '!=', 6)
                                    ->where('bancos_id', '!=', 7)
                                    ->where('bancos_id', '!=', 9)
                                    ->groupBy('categoria_id')->get();
        $this->layout->bancosi = BancoReportePresupuesto::select(DB::raw('bancos_id, sum(total) as total'))
                                    ->where('year', $this->layout->periodo)
                                    ->where('categoria_id', 0)
                                    ->where('subcategoria_id', 0)
                                    ->where('mes', 'Enero')
                                    ->where('bancos_id', '!=', 6)
                                    ->where('bancos_id', '!=', 7)
                                    ->where('bancos_id', '!=', 9)
                                    ->groupBy('bancos_id')->get();
        /*Categoría Saldo Inicial 2*/
        $this->layout->categoriasi2 = BancoReportePresupuesto::select(DB::raw('categoria_id, sum(total) as total'))
                                    ->where('year', $this->layout->periodo)
                                    ->where('categoria_id', 4)
                                    ->where('bancos_id', '!=', 6)
                                    ->where('bancos_id', '!=', 7)
                                    ->where('bancos_id', '!=', 9)
                                    ->groupBy('categoria_id')->get();
        $this->layout->bancosi2 = BancoReportePresupuesto::select(DB::raw('bancos_id, sum(total) as total'))
                                    ->where('year', $this->layout->periodo)
                                    ->where('categoria_id', 4)
                                    ->where('bancos_id', '!=', 6)
                                    ->where('bancos_id', '!=', 7)
                                    ->where('bancos_id', '!=', 9)
                                    ->groupBy('bancos_id')->get();
        /*Categorías Positivas*/
        $this->layout->categoriasp = BancoReportePresupuesto::select(DB::raw('categoria_id, sum(total) as total'))
                                    ->where('year', $this->layout->periodo)
                                    ->where('total', '>=', 0)
                                    ->where('categoria_id', '!=', 0)
                                    ->where('categoria_id', '!=', 4)
                                    ->where('bancos_id', '!=', 6)
                                    ->where('bancos_id', '!=', 7)
                                    ->where('bancos_id', '!=', 9)
                                    ->groupBy('categoria_id')
                                    ->orderBy('categoria_id')->get();
        /*Categorías Negativas*/
        $this->layout->categoriasn = BancoReportePresupuesto::select(DB::raw('categoria_id, sum(total) as total'))
                                    ->where('year', $this->layout->periodo)
                                    ->where('total', '<', 0)
                                    ->where('categoria_id', '!=', 0)
                                    ->where('categoria_id', '!=', 4)
                                    ->where('bancos_id', '!=', 6)
                                    ->where('bancos_id', '!=', 7)
                                    ->where('bancos_id', '!=', 9)
                                    ->groupBy('categoria_id')
                                    ->orderBy('categoria_id')->get();
        /*Categoría Saldo Inicial*/
        $this->layout->categoriasf = BancoReportePresupuesto::select(DB::raw('categoria_id, sum(total) as total'))
                                    ->where('year', $this->layout->periodo)
                                    ->where('categoria_id', 0)
                                    ->where('subcategoria_id', 1)
                                    ->where('mes', 'Diciembre')
                                    ->where('bancos_id', '!=', 6)
                                    ->where('bancos_id', '!=', 7)
                                    ->where('bancos_id', '!=', 9)
                                    ->groupBy('categoria_id')->get();
        $this->layout->bancosf = BancoReportePresupuesto::select(DB::raw('bancos_id, sum(total) as total'))
                                    ->where('year', $this->layout->periodo)
                                    ->where('categoria_id', 0)
                                    ->where('bancos_id', '!=', 6)
                                    ->where('bancos_id', '!=', 7)
                                    ->where('bancos_id', '!=', 9)
                                    ->where('subcategoria_id', 1)
                                    ->where('mes', 'Diciembre')
                                    ->groupBy('bancos_id')->get();
    }

    public function prestamos()
    {
        $this->layout              = View::make('sistema.reporte.prestamos');
        $this->layout->prestamos   = BancoPrest::where('user_id', Input::get('operador'))->whereBetween('fecha', [Input::get('fecha1'),Input::get('fecha2')])->get();
        $this->layout->operador    = Operador::where('user_id', Input::get('operador'))->first();
        $this->layout->title       = 'Prestamos '. $this->layout->operador->user->first_name.' '.$this->layout->operador->user->last_name;
        $this->layout->breadcrumb  = array(
          array(
                        'title' => 'Inicio',
                        'link'  => '/',
                        'icon'  => 'fas fa-home'
                      ),
                      array(
                        'title'     => 'Prestamos '. $this->layout->operador->user->first_name.' '.$this->layout->operador->user->last_name,
                        'link'      => 'reporte',
                        'icon'      => 'fas fa-wallet'
                      ),
        );
    }

    public function sueldos()
    {
        // $fecha1 = Input::get('fecha1') . ' 00:00:00';
        // $fecha2 = Input::get('fecha2') . ' 23:59:59';
        // $controlesvehiculares = ControlVehicular::where('user_id', Input::get('operador'))->whereBetween('fecha', [$fecha1,$fecha2])->orderBy('fecha')->get();
        // foreach($controlesvehiculares as $value){
        //     dump($value->toneladas);
        // }
        // return dump($controlesvehiculares);

        $this->layout           = View::make('sistema.reporte.sueldos');
        $this->layout->title    = 'Detalle de Sueldo';

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
                      array(
            'title' => 'Inicio',
            'link'  => '/',
            'icon'  => 'fas fa-home'
                      ),
                      array(
            'title' => 'Detalle de Sueldo',
            'link'  => 'sueldos',
            'icon'  => 'fas fa-wallet'
                      ),
                    );
        $this->layout->fecha1 = Input::get('fecha1') . ' 00:00:00';
        $this->layout->fecha2 = Input::get('fecha2') . ' 23:59:59';
        $this->layout->prestamos    = BancoPrest::where('user_id', Input::get('operador'))->whereBetween('fecha', [$this->layout->fecha1,$this->layout->fecha2])->get();
        $this->layout->operador     = Operador::withTrashed()->where('user_id', Input::get('operador'))->first();
        $this->layout->asignaciones = Asignacion::where('user_id', Input::get('operador'))->where('terminado', 'Ruta Finalizada')->whereBetween('updated_at', [$this->layout->fecha1,$this->layout->fecha2])->get();
        $this->layout->controlesvehiculares = ControlVehicular::where('user_id', Input::get('operador'))->whereBetween('fecha', [$this->layout->fecha1,$this->layout->fecha2])->orderBy('fecha')->get();
        $comprobantes = ComprobanteCombustible::where('user_id', Input::get('operador'))->whereBetween('created_at', [$this->layout->fecha1,$this->layout->fecha2])->lists('combustible_id');
        $this->layout->comprobantesvista = ComprobanteCombustibleVista::whereRaw('id in (select combustible_id from ram_comprobantes_combustible where user_id='.Input::get('operador').' and created_at between "'.$this->layout->fecha1.'" and "'.$this->layout->fecha2.'")')->get();
        $this->layout->gastos = Gasto::where('user_id',Input::get('operador'))->whereBetween('fecha', [$this->layout->fecha1,$this->layout->fecha2])->get();

    }

    public function sueldos_gastos()
    {

        $this->layout           = View::make('sistema.reporte.sueldos_gastos');
        $this->layout->title    = 'Detalle de Sueldo';

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
                      array(
            'title' => 'Inicio',
            'link'  => '/',
            'icon'  => 'fas fa-home'
                      ),
                      array(
            'title' => 'Detalle de Sueldo',
            'link'  => 'sueldos',
            'icon'  => 'fas fa-wallet'
                      ),
                    );
        $this->layout->fecha1 = Input::get('fecha1') . ' 00:00:00';
        $this->layout->fecha2 = Input::get('fecha2') . ' 23:59:59';
        $this->layout->prestamos    = BancoPrest::where('user_id', Input::get('operador'))->whereBetween('fecha', [$this->layout->fecha1,$this->layout->fecha2])->get();
        $this->layout->operador     = Operador::withTrashed()->where('user_id', Input::get('operador'))->first();
        $this->layout->asignaciones = Asignacion::where('user_id', Input::get('operador'))->where('terminado', 'Ruta Finalizada')->whereBetween('updated_at', [$this->layout->fecha1,$this->layout->fecha2])->get();
        $this->layout->controlesvehiculares = ControlVehicular::where('user_id', Input::get('operador'))->whereBetween('fecha', [$this->layout->fecha1,$this->layout->fecha2])->orderBy('fecha')->get();
        $comprobantes = ComprobanteCombustible::where('user_id', Input::get('operador'))->whereBetween('created_at', [$this->layout->fecha1,$this->layout->fecha2])->lists('combustible_id');
        $this->layout->comprobantesvista = ComprobanteCombustibleVista::whereRaw('id in (select combustible_id from ram_comprobantes_combustible where user_id='.Input::get('operador').' and created_at between "'.$this->layout->fecha1.'" and "'.$this->layout->fecha2.'")')->get();
        $this->layout->gastos = Gasto::where('user_id',Input::get('operador'))->whereBetween('fecha', [$this->layout->fecha1,$this->layout->fecha2])->get();

        $this->layout->facturas = FacturaUnidadInicial::whereBetween('fecha_pago',[Input::get('fecha1'),Input::get('fecha2')])->where('unidad_id',$this->layout->operador->unidad_id)->orderBy('fecha_pago')->get();
        //$this->layout->combustibles = FacturaUnidad::whereBetween('fecha_pago',[Input::get('fecha1'),Input::get('fecha2')])->where('unidad_id',$this->layout->operador->unidad_id)->orderBy('fecha_pago')->get();
        $this->layout->combustibles = ComprobanteCombustibleVista::whereBetween('fecha', [$this->layout->fecha1,$this->layout->fecha2])->where('user_id', Input::get('operador'))->get();
        $this->layout->gastos_no_maniobra = Gasto::where('user_id',Input::get('operador'))->where('descripcion','NOT LIKE','%maniob%')->whereBetween('fecha', [$this->layout->fecha1,$this->layout->fecha2])->get();

    }

    public function saveSueldos()
    {
      $new = Input::all();
      $sueldo = new Sueldo();

      // attempt validation
      if (!$sueldo->validate($new))
      {
        $errors = $sueldo->errors();
        return Redirect::back()->withErrors($errors);
      }
      Sueldo::create($new);
      return Redirect::back()->with('success','Se han guardado las fechas con éxito.');
    }

    public function operSueldos($id)
    {
      $this->layout           = View::make('sistema.operador.sueldo');
        $this->layout->title    = 'Detalle de Sueldo';
        $this->layout->sueldos  = Sueldo::where('operador_id',$id)->get();
        $this->layout->operador = $id;
        $this->layout->user     = User::find($id);

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
                      array(
            'title' => 'Inicio',
            'link'  => '/',
            'icon'  => 'fas fa-home'
                      ),
                      array(
            'title' => 'Detalle de Sueldos',
            'link'  => 'operador',
            'icon'  => 'fas fa-user'
                      ),
                    );
    }

    public function combustibles()
    {
      $this->layout           = View::make('sistema.reporte.combustibles');
      $this->layout->title    = 'Consumo de Combustible';
      $this->layout->unidades = Unidad::all();
      $this->layout->fecha1 = Input::get('fecha1') . ' 00:00:00';
      $this->layout->fecha2 = Input::get('fecha2') . ' 23:59:59';

      // add breadcrumb to current page
      $this->layout->breadcrumb = array(
                    array(
          'title' => 'Inicio',
          'link'  => '/',
          'icon'  => 'fas fa-home'
                    ),
                    array(
          'title' => 'Consumo de Combustible',
          'link'  => 'combustible',
          'icon'  => 'fas fa-gas-pump'
                    ),
                  );
    }

    public function proveedores()
    {
      $this->layout           = View::make('sistema.reporte.proveedores');
      $this->layout->title    = 'Facturas de Proveedores';
      $this->layout->fecha1 = Input::get('fecha1') . ' 00:00:00';
      $this->layout->fecha2 = Input::get('fecha2') . ' 23:59:59';
      $this->layout->proveedores = Proveedor::whereBetween('fecha',[$this->layout->fecha1,$this->layout->fecha2])->get();

      // add breadcrumb to current page
      $this->layout->breadcrumb = array(
                    array(
          'title' => 'Inicio',
          'link'  => '/',
          'icon'  => 'fas fa-home'
                    ),
                    array(
          'title' => 'Facturas de Proveedores',
          'link'  => 'proveedores',
          'icon'  => 'fas fa-user-tie'
                    ),
                  );
    }

    public function proveedoresticket()
    {
      $this->layout = View::make('sistema.reporte.proveedores_ticket');
      $this->layout->tickets = ComprobanteCombustibleVista::where('ticket','like','%'.Input::get('ticket').'%')->get();
    }

    public function proveedoresfactura()
    {
      $this->layout           = View::make('sistema.reporte.proveedores_factura');
      $this->layout->proveedores = Proveedor::where('factura','like','%'.Input::get('factura').'%')->get();
    }

    public function facturas()
    {
      $this->layout           = View::make('sistema.reporte.factura');
      $this->layout->title    = 'Facturas de Clientes';
      $this->layout->fecha1 = Input::get('fecha1') . ' 00:00:00';
      $this->layout->fecha2 = Input::get('fecha2') . ' 23:59:59';
      $this->layout->facturas = Factura::whereBetween('fecha_pago',[$this->layout->fecha1,$this->layout->fecha2])->get();

      // add breadcrumb to current page
      $this->layout->breadcrumb = array(
                    array(
          'title' => 'Inicio',
          'link'  => '/',
          'icon'  => 'fas fa-home'
                    ),
                    array(
          'title' => 'Facturas de Clientes',
          'link'  => 'facturas',
          'icon'  => 'fas fa-user-tie'
                    ),
                  );
    }

    public function facturasticket()
    {
      $this->layout           = View::make('sistema.reporte.facturaticket');
      $this->layout->title    = 'Facturas de Clientes';
      $this->layout->facturas = Factura::where('factura','like','%'.Input::get('ticket').'%')->get();

      // add breadcrumb to current page
      $this->layout->breadcrumb = array(
                    array(
          'title' => 'Inicio',
          'link'  => '/',
          'icon'  => 'fas fa-home'
                    ),
                    array(
          'title' => 'Facturas de Clientes',
          'link'  => 'facturas',
          'icon'  => 'fas fa-user-tie'
                    ),
                  );
    }

    public function deudas()
    {
      $queryResult                = DB::select('call ram_deudas');
      $queryFacturado             = DB::select('select * from ram_porpagar_facturado');
      
      $this->layout               = View::make('sistema.reporte.deudas');
      $this->layout->title        = 'Cuentas por Cobrar';
      $this->layout->deudas       = $queryResult;
      $this->layout->deudas_fact  = $queryFacturado;
      //$this->layout->controles    = ControlVehicular::whereRaw('id not in (select control_id from ram_facturas_controles) and pagado = ""')->whereNull('factura_id')->orderBy('created_at','desc')->get();
      //$this->layout->cotizaciones = Cotizacion::whereRaw('id not in (select cotizacion_id from ram_facturas) AND id IN (SELECT cotizacion_id from ram_asignaciones)')->orderBy('created_at','desc')->get();

      $this->layout->controles    = ControlVehicular::select(DB::raw('sum(cantidad) as cantidad, origen'))->whereRaw('id not in (select control_id from ram_facturas_controles) and pagado = ""')->whereNull('factura_id')->groupBy('origen')->orderBy('created_at','desc')->get();

      $this->layout->cotizaciones = Cotizacion::select(DB::raw('sum(propuesta) as propuesta,cliente_id'))->whereRaw('id not in (select cotizacion_id from ram_facturas) AND id IN (SELECT cotizacion_id from ram_asignaciones)')->groupBy('cliente_id')->orderBy('created_at','desc')->get();
      $this->layout->facturas     = Factura::where('pagada',0)->get();

      // add breadcrumb to current page
      $this->layout->breadcrumb = array(
                    array(
          'title' => 'Inicio',
          'link'  => '/',
          'icon'  => 'fas fa-home'
                    ),
                    array(
          'title' => 'Cuentas por Cobrar',
          'link'  => 'deudas',
          'icon'  => 'fas fa-user-tie'
                    ),
                  );
    }

    public function unidad()
    {


      $this->layout           = View::make('sistema.reporte.facturas_unidades');
      $this->layout->title    = 'Facturación Por Unidad';
      $this->layout->facturas = FacturaUnidadInicial::whereBetween('fecha_pago',[Input::get('fecha1'),Input::get('fecha2')])->where('unidad_id',Input::get('unidad'))->orderBy('fecha_pago')->get();
      $this->layout->combustibles = FacturaUnidad::whereBetween('fecha_pago',[Input::get('fecha1'),Input::get('fecha2')])->where('unidad_id',Input::get('unidad'))->orderBy('fecha_pago')->get();
      $this->layout->gastos = GastoUnidad::whereBetween('fecha',[Input::get('fecha1'),Input::get('fecha2')])->where('unidad_id',Input::get('unidad'))->orderBy('fecha')->get();

      // add breadcrumb to current page
      $this->layout->breadcrumb = array(
                    array(
          'title' => 'Inicio',
          'link'  => '/',
          'icon'  => 'fas fa-home'
                    ),
                    array(
          'title' => 'Facturación Por Unidad',
          'link'  => 'unidad',
          'icon'  => 'fas fa-calculator'
                    ),
                  );

    }

    public function unidadoperador()
    {

      if (Input::get('rango') == 1) {
        $this->layout = View::make('sistema.reporte.unidades');
      } elseif (Input::get('rango')==2) {
        $this->layout = View::make('sistema.reporte.unidades_2020');
      } elseif (Input::get('rango')==3) {
        $this->layout = View::make('sistema.reporte.unidades_2021');
      } elseif (Input::get('rango')==4) {
          $this->layout = View::make('sistema.reporte.unidades_2022');
      } elseif (Input::get('rango')==5) {
        $this->layout = View::make('sistema.reporte.unidades_2023');
      } elseif (Input::get('rango')==6) {
        $this->layout = View::make('sistema.reporte.unidades_2024');
      }
      $this->layout->fecha1 = Input::get('fecha1') . ' 00:00:00';
      $this->layout->fecha2 = Input::get('fecha2') . ' 23:59:59';
      $this->layout->title    = 'Reporte Unidades / Operadores';
      $this->layout->unidadesoperadores = DB::table('reporte_unidades_view')->whereBetween('fecha',[$this->layout->fecha1,$this->layout->fecha2])->groupBy('fecha')->get();

      // add breadcrumb to current page
      $this->layout->breadcrumb = array(
                    array(
          'title' => 'Inicio',
          'link'  => '/',
          'icon'  => 'fas fa-home'
                    ),
                    array(
          'title' => 'Reporte Unidades / Operadores',
          'link'  => '',
          'icon'  => 'fas fa-truck'
                    ),
                  );
    }

    public function clientesmensual()
    {

      switch (Input::get('rango')) {
        case 1:
          $anno = 2018;
          break;
        case 2:
          $anno = 2019;
          break;
        case 3:
          $anno = 2020;
          break;
        case 4:
          $anno = 2021;
          break;
        case 5:
          $anno = 2022;
          break;
        case 6:
          $anno = 2023;
          break;
        case 7:
          $anno = 2024;
          break;
      }

      $this->layout = View::make('sistema.reporte.clientes');
      $this->layout->title    = 'Reporte Mensual Clientes del '. $anno;
      $this->layout->anno = $anno;

      // add breadcrumb to current page
      $this->layout->breadcrumb = array(
                    array(
          'title' => 'Inicio',
          'link'  => '/',
          'icon'  => 'fas fa-home'
                    ),
                    array(
          'title' => 'Reporte Mensual Clientes del '. $anno,
          'link'  => '',
          'icon'  => 'fas fa-truck'
                    ),
                  );
    }
}
