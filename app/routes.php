<?php //phpcs:ignore

Route::get('/', function () {
    return View::make('pagina/home');
});

Route::get('test', function () {
    return View::make('hello');
});

Route::get('nosotros', function () {
    return View::make('pagina/nosotros');
});

Route::get('galeria', function () {
    return View::make('pagina/galeria');
});

Route::get('contacto', function () {
    return View::make('pagina/contacto');
});

Route::post('contacto', array(
    'as' => 'sendMail',
    'uses' => 'HomeController@mail'
));

Config::set('syntara::views.dashboard-index', 'index');

View::composer('index', function ($view) {
    $view->with('user', Sentry::getUser())
         ->with('admin', Sentry::findGroupByName('Admin'))
         ->with('usuarios', Sentry::findGroupByName('Administrativos'))
         ->with('operadores', Sentry::findGroupByName('Operador'))
         ->with('operadoresesp', Sentry::findGroupByName('OperadorEsp'))
         ->with('unidades_list', DB::table('unidades_list')->orderBy('unidad')->lists('unidad', 'id'))
         ->with('gasolineras_list', Gasolinera::orderBy('gasolinera')->lists('gasolinera', 'id'))
         ->with('asignaciones', Asignacion::where('user_id', Sentry::getUser()->id)->where('terminado', '!=', 'Ruta Finalizada')->get()) //phpcs:ignore
         ->with('cotizaciones', Cotizacion::all()->sortByDesc('created_at'))
         ->with('unidades', Unidad::orderBy('vigencia')->limit(5)->get())
         ->with('ops', Operador::where('vigencia', '!=', '0000-00-00')->orderBy('vigencia')->limit(5)->get())
         ->with('medica', Operador::where('medica', '!=', '0000-00-00')->orderBy('medica')->limit(5)->get())
         ->with('opes', Operador::whereNull('deleted_at')->get())
         ->with('clientes', Cliente::orderBy('cliente')->get());
});

View::composer('dashboard.layouts.dashboard.master', function ($view) {
    $view->with('siteName', 'Fletes RAM - 149');
});

View::composer('dashboard.layouts.dashboard.master', function ($view) {
    $view->nest('navPages', 'left-nav');
});


/*============================================
=            Variables para Index            =
============================================*/

/*----------  Tipos de Unidad  ----------*/
View::composer('catalogos.tipounidad.index', function ($view) {
    $view->with('tiposunidades', TipoUnidad::all());
});

/*----------  Rendimiento de Combustible  ----------*/
View::composer('catalogos.rendimiento.index', function ($view) {
    $view->with('rendimientos', Rendimiento::all());
});

/*----------  Gasolineras Autorizadas  ----------*/
View::composer('catalogos.gasolinera.index', function ($view) {
    $view->with('gasolineras', Gasolinera::all());
});

View::composer('catalogos.codigo.index', function ($view) {
    $view->with('municipios', Municipio::all()->sortBy('estado'));
});

/*----------  Unidades  ----------*/
View::composer('catalogos.unidad.index', function ($view) {
    $view->with('unidades', Unidad::all()->sortBy('Unidad'));
});

/*---------- Bancos ----------*/
View::composer('catalogos.banco.index', function ($view) {
    $view->with('bancos', Banco::all());
});

/*---------- Periodo Bancario ----------*/
View::composer('catalogos.periodo.index', function ($view) {
    $view->with('periodo', BancoPeriodo::find(1));
});

/*---------- Catalogo de Bancos --------*/
View::composer('catalogos.banco_categoria.index', function ($view) {
    $view->with('categorias', BancoCategoria::all());
});

View::composer('catalogos.banco_subcategoria.index', function ($view) {
    $view->with('subcategorias', BancoSubCategoria::all());
});

View::composer('catalogos.origen.index', function ($view) {
    $view->with('origenes', Origen::all());
});

/*----------  Clientes  ----------*/
View::composer('sistema.cliente.index', function ($view) {
    $view->with('clientes', Cliente::all());
});

/*----------  Proveedores  ----------*/
View::composer('sistema.cat_proveedor.index', function ($view) {
    $view->with('proveedores', CatProveedor::all());
});

/*----------  Costo Combustible  ----------*/
View::composer('sistema.combustible.index', function ($view) {
    $view->with('diesel', CostoCombustible::find(1))
         ->with('magna', CostoCombustible::find(2))
         ->with('premium', CostoCombustible::find(3));
});

View::composer('sistema.operador.index', function ($view) {
    $view->with('operadores', Operador::all());
});

View::composer('sistema.ruta.index', function ($view) {
    $view->with('rutas', Ruta::all());
});

View::composer('sistema.cotizacion.index', function ($view) {
    $view->with('cotizaciones', Cotizacion::all()->sortByDesc('created_at'));
});

View::composer('sistema.movimiento.index', function ($view) {
    $view->with('efectivos', Banco::where('banco', 'like', '%Efectivo%')->get())
         ->with('bancos', Banco::where('banco', 'not like', '%Efectivo%')->get())
         ->with('periodo', DB::table('bancos_periodos')->find(1));
});

View::composer('sistema.asignacion.index', function ($view) {
    $view->with('asignaciones', Asignacion::all());
});

/*------------------ Operadores ----------------*/
View::composer('sistema.operador.create', function ($view) {
    $view->with('unidades_list', DB::table('unidades_list')->orderBy('unidad')->lists('unidad', 'id'));
});

/*=====  End of Variables para Index  ======*/

Route::group(array('before' => 'basicAuth|hasPermissions', 'prefix' => 'catalogos'), function () {

    /*=========================================
    =            Tipos de Unidades            =
    =========================================*/

    Route::get('tipodeunidad', array(
        'as'   => 'listTipoUnidad',
        'uses' => 'TipoUnidadController@index'
    ));

    Route::get('tipodeunidad/crear', array(
        'as'   => 'newTipoUnidad',
        'uses' => 'TipoUnidadController@create'
    ));

    Route::post('tipodeunidad/crear', array(
        'as'   => 'newTipoUnidadPost',
        'uses' => 'TipoUnidadController@store'
    ));

    Route::get('tipodeunidad/{id}/editar', array(
        'as'   => 'putTipoUnidad',
        'uses' => 'TipoUnidadController@edit'
    ));

    Route::post('tipodeunidad/{id}/editar', array(
        'as' => 'putTipoUnidadPost',
        'uses' => 'TipoUnidadController@update'
    ));

    Route::post('tipodeunidad/{id}/destroy', array(
        'as'   => 'deleteTipoUnidad',
        'uses' => 'TipoUnidadController@destroy'
    ));

    /*=====  End of Tipos de Unidades  ======*/

    /*====================================
    =            Rendimientos            =
    ====================================*/

    Route::get('rendimiento', array(
        'as'   => 'listRendimiento',
        'uses' => 'RendimientoController@index'
    ));

    Route::get('rendimiento/crear', array(
        'as'   => 'newRendimiento',
        'uses' => 'RendimientoController@create'
    ));

    Route::post('rendimiento/crear', array(
        'as'   => 'newRendimientoPost',
        'uses' => 'RendimientoController@store'
    ));

    Route::get('rendimiento/{id}/editar', array(
        'as' => 'putRendimiento',
        'uses' => 'RendimientoController@edit'
    ));

    Route::post('rendimiento/{id}/editar', array(
        'as' => 'putRendimientoPost',
        'uses' => 'RendimientoController@update'
    ));

    Route::post('rendimiento/{id}/destroy', array(
        'as'   => 'deleteRendimiento',
        'uses' => 'RendimientoController@destroy'
    ));

    /*=====  End of Rendimientos  ======*/

    /*===================================
    =            Gasolineras            =
    ===================================*/

    Route::get('gasolinera', array(
        'as'   => 'listGasolinera',
        'uses' => 'GasolineraController@index'
    ));

    Route::get('gasolinera/crear', array(
        'as'   => 'newGasolinera',
        'uses' => 'GasolineraController@create'
    ));

    Route::post('gasolinera/crear', array(
        'as'   => 'newGasolineraPost',
        'uses' => 'GasolineraController@store'
    ));

    Route::get('gasolinera/{id}/editar', array(
        'as'   => 'putGasolinera',
        'uses' => 'GasolineraController@edit'
    ));

    Route::post('gasolinera/{id}/editar', array(
        'as'   => 'putGasolineraPost',
        'uses' => 'GasolineraController@update'
    ));

    Route::post('gasolinera/{id}/destroy', array(
        'as'   => 'deleteGasolinera',
        'uses' => 'GasolineraController@destroy'
    ));

    /*=====  End of Gasolineras  ======*/

    /*===================================
    =            Unidades            =
    ===================================*/

    Route::get('unidad', array(
        'as'   => 'listUnidad',
        'uses' => 'UnidadController@index'
    ));

    Route::get('unidad/crear', array(
        'as'   => 'newUnidad',
        'uses' => 'UnidadController@create'
    ));

    Route::post('unidad/crear', array(
        'as'   => 'newUnidadPost',
        'uses' => 'UnidadController@store'
    ));

    Route::get('unidad/{id}/editar', array(
        'as'   => 'putUnidad',
        'uses' => 'UnidadController@edit'
    ));

    Route::post('unidad/{id}/editar', array(
      'as'   => 'putUnidadPost',
      'uses' => 'UnidadController@update'
    ));

    Route::post('unidad/{id}/destroy', array(
      'as'   => 'deleteUnidad',
      'uses' => 'UnidadController@destroy'
    ));

    /*=====  End of Unidades  ======*/

    /*===================================
    =            Bancos                 =
    ===================================*/

    Route::get('banco', array(
      'as'   => 'listBanco',
      'uses' => 'BancoController@index'
    ));

    Route::get('banco/crear', array(
      'as'   => 'newBanco',
      'uses' => 'BancoController@create'
    ));

    Route::post('banco/crear', array(
      'as'   => 'newBancoPost',
      'uses' => 'BancoController@store'
    ));

    Route::get('banco/{id}/editar', array(
      'as'   => 'putBanco',
      'uses' => 'BancoController@edit'
    ));

    Route::post('banco/{id}/editar', array(
      'as'   => 'putBancoPost',
      'uses' => 'BancoController@update'
    ));

    Route::post('banco/{id}/destroy', array(
      'as'   => 'deleteBanco',
      'uses' => 'BancoController@destroy'
    ));

    Route::get('banco/periodo', array(
      'as'   => 'listBancoPeriodo',
      'uses' => 'BancoPeriodoController@index'
    ));

    Route::post('banco/periodo/edit', array(
      'as'   => 'listBancoPeriodoPut',
      'uses' => 'BancoPeriodoController@update'
    ));

    /*=====  End of Bancos  ======*/

    /*================================
    =     Categorías bancarias  =
    ================================*/

    Route::get('categoria', array(
      'as'   => 'listBancoCategoria',
      'uses' => 'BancoCategoriaController@index'
    ));

    Route::get('categoria/crear', array(
      'as'   => 'newBancoCategoria',
      'uses' => 'BancoCategoriaController@create'
    ));

    Route::post('categoria/crear', array(
      'as'   => 'newBancoCategoriaPost',
      'uses' => 'BancoCategoriaController@store'
    ));

    Route::get('categoria/{id}/editar', array(
      'as'   => 'putBancoCategoria',
      'uses' => 'BancoCategoriaController@edit'
    ));

    Route::post('categoria/{id}/editar', array(
      'as'   => 'putBancoCategoriaPost',
      'uses' => 'BancoCategoriaController@update'
    ));

    Route::post('categoria/{id}/destroy', array(
      'as'   => 'deleteBancoCategoria',
      'uses' => 'BancoCategoriaController@destroy'
    ));

    /*=====  End of Categorías bancarias=*/

    /*================================
    =     Subcategorias bancarias  =
    ================================*/

    Route::get('subcategoria', array(
      'as'   => 'listBancoSubCategoria',
      'uses' => 'BancoSubCategoriaController@index'
    ));

    Route::get('subcategoria/crear', array(
      'as'   => 'newBancoSubCategoria',
      'uses' => 'BancoSubCategoriaController@create'
    ));

    Route::post('subcategoria/crear', array(
      'as'   => 'newBancoSubCategoriaPost',
      'uses' => 'BancoSubCategoriaController@store'
    ));

    Route::get('subcategoria/{id}/editar', array(
      'as'   => 'putBancoSubCategoria',
      'uses' => 'BancoSubCategoriaController@edit'
    ));

    Route::post('subcategoria/{id}/editar', array(
      'as'   => 'putBancoSubCategoriaPost',
      'uses' => 'BancoSubCategoriaController@update'
    ));

    Route::post('subcategoria/{id}/destroy', array(
      'as'   => 'deleteBancoSubCategoria',
      'uses' => 'BancoSubCategoriaController@destroy'
    ));

    /*=====  End of Subcategorias bancarias=*/

    Route::resource('origen', 'OrigenController');
});

/*===============================
=            Sistema            =
===============================*/

Route::group(array('before' => 'basicAuth|hasPermissions'), function () {

    /*================================
    =            Clientes            =
    ================================*/

    Route::get('cliente', array(
        'as'   => 'listCliente',
        'uses' => 'ClienteController@index'
    ));

    Route::get('cliente/crear', array(
        'as'   => 'newCliente',
        'uses' => 'ClienteController@create'
    ));

    Route::post('cliente/crear', array(
        'as'   => 'newClientePost',
        'uses' => 'ClienteController@store'
    ));

    Route::get('cliente/{id}/editar', array(
        'as'   => 'putCliente',
        'uses' => 'ClienteController@edit'
    ));

    Route::post('cliente/{id}/editar', array(
        'as'   => 'putClientePost',
        'uses' => 'ClienteController@update'
    ));

    Route::post('cliente/{id}/destroy', array(
        'as'   => 'deleteCliente',
        'uses' => 'ClienteController@destroy'
    ));

    /*=====  End of Clientes  ======*/

    /*===================================
    =            Combustible            =
    ===================================*/

    Route::get('costo_combustible', array(
        'as'   => 'listCombustible',
        'uses' => 'CostoCombustibleController@index'
    ));
    Route::post('costo_combustible/{id}/editar', array(
        'as'   => 'putCombustiblePost',
        'uses' => 'CostoCombustibleController@update'
    ));

    /*=====  End of Combustible  ======*/

    /*==================================
    =            Operadores            =
    ==================================*/

    Route::get('operador', array(
        'as'   => 'listOperador',
        'uses' => 'OperadorController@index'
    ));

    Route::get('operador/crear', array(
        'as'   => 'newOperador',
        'uses' => 'OperadorController@create'
    ));

    Route::post('operador/crear', array(
        'as'   => 'newOperadorPost',
        'uses' => 'OperadorController@store'
    ));

    Route::get('operador/{id}/editar', array(
        'as'   => 'putOperador',
        'uses' => 'OperadorController@edit'
    ));

    Route::post('operador/{id}/editar', array(
        'as'   => 'putOperadorPost',
        'uses' => 'OperadorController@update'
    ));

    Route::post('operador/{id}/destroy', array(
        'as'   => 'deleteOperador',
        'uses' => 'OperadorController@destroy'
    ));

    Route::get('operador/{id}/sueldos', array(
        'as'  => 'operSueldos',
        'uses'=> 'ReporteController@operSueldos'
    ));

    /*=====  End of Operadores  ======*/

    /*=============================
    =            Rutas            =
    =============================*/

    Route::get('ruta', array(
        'as'   => 'listRuta',
        'uses' => 'RutaController@index'
    ));

    Route::get('ruta/crear', array(
        'as'   => 'newRuta',
        'uses' => 'RutaController@create'
    ));

    Route::post('ruta/crear', array(
        'as'   => 'newRutaPost',
        'uses' => 'RutaController@store'
    ));

    Route::get('ruta/{id}/editar', array(
        'as'   => 'putRuta',
        'uses' => 'RutaController@edit'
    ));

    Route::post('ruta/{id}/editar', array(
        'as'   => 'putRutaPost',
        'uses' => 'RutaController@update'
    ));

    Route::post('ruta/{id}/destroy', array(
        'as'   => 'deleteRuta',
        'uses' => 'RutaController@destroy'
    ));

    Route::get('ruta/{id}/show', array(
      'as'   => 'showRuta',
      'uses' => 'RutaController@show'
    ));


    /*=====  End of Rutas  ======*/

    /*=============================
    =            Cotizaciones            =
    =============================*/

    Route::get('cotizacion', array(
        'as'   => 'listCotizacion',
        'uses' => 'CotizacionController@index'
    ));

    Route::get('cotizacion/crear', array(
        'as'   => 'newCotizacion',
        'uses' => 'CotizacionController@create'
    ));

    Route::post('cotizacion/crear', array(
        'as'   => 'newCotizacionPost',
        'uses' => 'CotizacionController@store'
    ));

    Route::get('cotizacion/{id}/editar', array(
        'as'   => 'putCotizacion',
        'uses' => 'CotizacionController@edit'
    ));

    Route::post('cotizacion/{id}/editar', array(
        'as'   => 'putCotizacionPost',
        'uses' => 'CotizacionController@update'
    ));

    Route::post('cotizacion/{id}/destroy', array(
        'as'   => 'deleteCotizacion',
        'uses' => 'CotizacionController@destroy'
    ));

    Route::get('cotizacion/{id}/show', array(
        'as'   => 'showCotizacion',
        'uses' => 'CotizacionController@show'
    ));

    Route::get('cotizacion/{id}/showpdf', array(
        'as'   => 'showCotizacionPdf',
        'uses' => 'CotizacionController@show_pdf'
    ));

    Route::get('cotizacion/{id}/send', array(
        'as'   => 'sendCotizacion',
        'uses' => 'CotizacionController@send'
    ));


    /*=====  End of Cotizaciones  ======*/

    /*========================================
    =            Asignaciones                =
    ========================================*/

    Route::get('asignacion', array(
      'as'   => 'listAsignacion',
      'uses' => 'AsignacionController@index'
    ));

    Route::get('asignacion/crear', array(
      'as'   => 'newAsignacion',
      'uses' => 'AsignacionController@create'
    ));

    Route::post('asignacion/crear', array(
      'as'   => 'newAsignacionPost',
      'uses' => 'AsignacionController@store'
    ));

    Route::get('asignacion/{id}/show', array(
      'as'   => 'showAsignacion',
      'uses' => 'AsignacionController@show'
    ));

    Route::get('asignacion/{id}/editar', array(
      'as'   => 'putAsignacion',
      'uses' => 'AsignacionController@edit'
    ));

    Route::post('asignacion/{id}/editar', array(
      'as'   => 'putAsignacionPost',
      'uses' => 'AsignacionController@update'
    ));

    Route::post('asignacion/{id}/delete', array(
      'as'   => 'deleteAsignacion',
      'uses' => 'AsignacionController@destroy'
    ));

    Route::get('asignacion/combustible/{id}/crear', array(
      'as'   => 'newAsignacionCombustible',
      'uses' => 'AsignacionCombustibleController@create'
    ));

    Route::post('asignacion/combustible/{id}/crear', array(
      'as'   => 'newAsignacionCombustiblePost',
      'uses' => 'AsignacionCombustibleController@store'
    ));

    Route::post('sistema_ram', array(
      'as'   => 'newAsignacionCombustibleEspPost',
      'uses' => 'AsignacionCombustibleController@storeEsp'
    ));

    Route::get('combustibleesp', array(
      'as'   => 'listCombustibleEsp',
      'uses' => 'AsignacionCombustibleController@index'
    ));

    Route::get('combustibleesp/{id}/show', array(
      'as'   => 'showAsignacionEsp',
      'uses' => 'AsignacionCombustibleController@show'
    ));

    Route::post('combustibleesp/{id}/delete', array(
      'as'   => 'deleteAsignacionEsp',
      'uses' => 'AsignacionCombustibleController@destroy'
    ));

    Route::post('combustible/{id}/destroy', array(
      'as'   => 'deleteAsignacionCom',
      'uses' => 'AsignacionCombustibleController@destroyComb'
    ));

    Route::get('ticket-guardado', array(
      'as'   => 'prueba',
      'uses' => 'AsignacionCombustibleController@prueba'
    ));

    /*=====  End of Asignaciones      ======*/

    /*========================================
    =            Movimientos Bancarios            =
    ========================================*/


    Route::get('movimiento', array(
      'as'   => 'listBancoMov',
      'uses' => 'BancoMovController@index'
    ));

    Route::get('movimiento/crear', array(
      'as'   => 'newBancoMov',
      'uses' => 'BancoMovController@create'
    ));

    Route::post('movimiento/crear', array(
      'as'   => 'newBancoMovPost',
      'uses' => 'BancoMovController@store'
    ));

    Route::get('movimiento/{id}/detalle', array(
      'as'   => 'showBancoMov',
      'uses' => 'BancoMovController@show'
    ));

    Route::post('movimiento/{id}/destroy', array(
      'as'   => 'deleteBancoMov',
      'uses' => 'BancoMovController@destroy'
    ));

    /*=====  End of Movimientos Bancarios  ======*/

    /*========================================
    =            Prestamos                  =
    ========================================*/

    Route::get('prestamo/crear', array(
      'as'   => 'newBancoPrest',
      'uses' => 'BancoPrestController@create'
    ));

    Route::post('prestamo/crear', array(
      'as'   => 'newBancoPrestPost',
      'uses' => 'BancoPrestController@store'
    ));

    Route::post('prestamo/{id}/delete', array(
      'as'   => 'deleteBancoPrest',
      'uses' => 'BancoPrestController@destroy'
    ));

    Route::get('prestamo/crear/salario', array(
      'as'   => 'newBancoPrestSueldo',
      'uses' => 'BancoPrestController@createsalario'
    ));

    Route::post('prestamo/crear/salario', array(
      'as'   => 'newBancoPrestSueldoPost',
      'uses' => 'BancoPrestController@storesalario'
    ));

    Route::get('prestamo/index', array(
      'as'   => 'listBancoPrestSueldo',
      'uses' => 'BancoPrestController@index'
    ));



    /*=====  End of Prestamos   ======*/

    /*========================================
    =            Códigos Postales            =
    ========================================*/

    Route::get('codigo', array(
        'as'   => 'listCodigo',
        'uses' => 'CodigoController@index'
    ));

    Route::post('codigo/crear', array(
        'as'   => 'newCodigoPost',
        'uses' => 'CodigoController@store'
    ));

    /*=====  End of Códigos Postales  ======*/

    /**
     *  REPORTES
     */

    Route::get('reporte', array(
       'as'   => 'listReporte',
       'uses' => 'ReporteController@index'
     ));

    Route::get('reporte/show', array(
      'as'   => 'showReporte',
      'uses' => 'ReporteController@reporte'
    ));

    Route::get('presupuesto', array(
      'as'   => 'showPresupuesto',
      'uses' => 'ReporteController@presupuesto'
    ));

    Route::get('presupuestoYear', array(
      'as'   => 'showPresupuesto',
      'uses' => 'ReporteController@presupuestoYear'
    ));

    Route::get('prestamos', array(
      'as'   => 'showPrestamos',
      'uses' => 'ReporteController@prestamos'
    ));

    Route::get('sueldos', array(
      'as'   => 'showSueldos',
      'uses' => 'ReporteController@sueldos'
    ));

    Route::get('sueldos_gastos', array(
      'as'   => 'showSueldos',
      'uses' => 'ReporteController@sueldos_gastos'
    ));

    Route::get('sueldos_guardar', array(
      'as'   => 'saveSueldos',
      'uses' => 'ReporteController@saveSueldos'
    ));

    Route::get('combustibles', array(
      'as'   => 'showCombustibles',
      'uses' => 'ReporteController@combustibles'
    ));

    Route::get('proveedores', array(
      'as'   => 'showProveedores',
      'uses' => 'ReporteController@proveedores'
    ));

    Route::get('proveedoresfactura', array(
      'as'   => 'showProveedoresFactura',
      'uses' => 'ReporteController@proveedoresfactura'
    ));

    Route::get('proveedoresticket', array(
      'as'   => 'showProveedoresTicket',
      'uses' => 'ReporteController@proveedoresticket'
    ));

    Route::get('facturas', array(
      'as'   => 'showFacturas',
      'uses' => 'ReporteController@facturas'
    ));

    Route::get('facturasticket', array(
      'as'   => 'showFacturas',
      'uses' => 'ReporteController@facturasticket'
    ));

    Route::get('deudas', array(
      'as'  => 'showDeudas',
      'uses' => 'ReporteController@deudas'
    ));

    Route::get('unidad', array(
      'as'  => 'showUnidades',
      'uses' => 'ReporteController@unidad'
    ));

    Route::get('unidadoperador', array(
      'as'  => 'showUnidadesOperadores',
      'uses' => 'ReporteController@unidadoperador'
    ));

    Route::get('clientesmensual', array(
      'as'  => 'showClientesMensual',
      'uses' => 'ReporteController@clientesmensual'
    ));

    Route::resource('control', 'ControlController');

    Route::resource('comprobante_combustible', 'ComprobanteCombustibleController');

    Route::resource('gasto', 'GastoController');

    Route::resource('factura', 'FacturaController');

    Route::resource('facturacontrol', 'FacturaControlController');

    Route::resource('combustible', 'CombustibleController');

    Route::resource('proveedor', 'ProveedorController');

    Route::resource('mantenimiento', 'MantenimientoController');

    Route::resource('cat_proveedor', 'CatProveedorController');

    Route::resource('bitacora', 'BitacoraController');

    Route::resource('foraneo', 'ForaneoController');

    Route::resource('foraneo_operador', 'ForaneoOperadorController');

    Route::resource('material', 'InventarioMaterialController');
    
    Route::resource('materialEntrada', 'InventarioMaterialEntradaController');
    
    Route::resource('materialSalida', 'InventarioMaterialSalidaController');

    Route::resource('llanta', 'InventarioLlantaController');

    Route::resource('llantaEntrada', 'LlantaEntradaController');
    
    Route::resource('llantaSalida', 'LlantaSalidaController');

    Route::resource('dieselAutorizado', 'DieselAutorizadoController');

    Route::resource('sueldoAdmin', 'SueldoAdminController');

    Route::resource('asistencia', 'AsistenciaController');



    /**
     *  REPORTES
     */
});

/*=====  End of Sistema  ======*/


/*====================================================
=            Catalogo de Códigos Postales            =
====================================================*/

Route::get('code/{id}', 'CodeController@show');

Route::get('api/dropdown', function () {
    $input     = Input::get('estado');
    $estado    = Estado::where('idEstado', $input)->get();
    $municipio = Municipio::where('idEstado', $input)->orderBy('municipio');
    return Response::make($municipio->get(['municipio','municipio']));
});

Route::get('api/gastos', function () {
    $input   = Input::get('cliente');
    $cliente = Cliente::find($input);
    return $cliente;
});

Route::get('api/rutas', function () {
    $input = Input::get('ruta');
    $ruta  = Ruta::find($input);
    return $ruta;
});

Route::get('api/tipo_unidad', function () {
    $input       = Input::get('tipo_unidad');
    $tipo_unidad = TipoUnidad::find($input);
    return $tipo_unidad;
});

Route::get('api/rendimiento', function () {
    $input = Input::get('tipo_unidad');
    $rendimientos = Rendimiento::where('tipo_de_unidad_id', $input)->orderBy('rendimiento');
    return Response::make($rendimientos->get(['id','rendimiento']));
});

Route::get('cotizacion/api/gastos', function () {
    $input   = Input::get('cliente');
    $cliente = Cliente::find($input);
    return $cliente;
});

Route::get('cotizacion/api/rutas', function () {
    $input = Input::get('ruta');
    $ruta  = Ruta::find($input);
    return $ruta;
});

Route::get('cotizacion/api/tipo_unidad', function () {
    $input       = Input::get('tipo_unidad');
    $tipo_unidad = TipoUnidad::find($input);
    return $tipo_unidad;
});

Route::get('cotizacion/api/rendimiento', function () {
    $input = Input::get('tipo_unidad');
    $rendimientos = Rendimiento::where('tipo_de_unidad_id', $input)->orderBy('rendimiento');
    return Response::make($rendimientos->get(['id','rendimiento']));
});

Route::get('api/categorias', function () {
    $input = Input::get('categoria_id');
    $subcategorias = BancoSubCategoria::where('categoria_id', $input)->orderBy('subcategoria');
    return Response::make($subcategorias->get(['id','subcategoria']));
});

Route::get('mantenimiento/api/categorias', function () {
    $input = Input::get('categoria_id');
    $subcategorias = BancoSubCategoria::where('categoria_id', $input)->orderBy('subcategoria');
    return Response::make($subcategorias->get(['id','subcategoria']));
});

Route::post('api/edita_cliente', array(
  'as'   => 'apiEditaCliente',
  'uses' => 'ApiController@editaCliente'
));

Route::post('api/edita_origen', array(
  'as'   => 'apiEditaOrigen',
  'uses' => 'ApiController@editaOrigen'
));

Route::post('api/edita_destino', array(
  'as'   => 'apiEditaDestino',
  'uses' => 'ApiController@editaDestino'
));

Route::post('api/edita_estatus', array(
  'as'   => 'apiEditaestatus',
  'uses' => 'ApiController@editaestatus'
));

Route::get(
    'recover_password/{id}/{resetCode}',
    array(
    'as'   => 'recover.newPassword',
    'uses' => 'RecoverController@newPassword')
);

Route::post(
    'recover_password/{id}/{resetCode}',
    array(
    'as'   => 'recover.newPasswordPost',
    'uses' => 'RecoverController@newPasswordPost')
);

Route::resource('recover', 'RecoverController');
