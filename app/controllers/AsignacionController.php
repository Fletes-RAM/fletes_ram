<?php

class AsignacionController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->layout              = View::make('sistema.asignacion.index');
        $this->layout->title       = 'Asignación de Rutas';
        $this->layout->breadcrumb  = array(
            array(
                        'title' => 'Inicio',
                        'link'  => '/',
                        'icon'  => 'fas fa-home'
                      ),
                      array(
                        'title'     => 'Asignación de Rutas',
                        'link'      => 'asignacion',
                        'icon'      => 'fas fa-truck'
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
        $this->layout              = View::make('sistema.asignacion.create');
        $this->layout->title       = 'Asignación de Rutas';
        $this->layout->cotizaciones_list = CotizacionList::orderBy('nombre')->lists('nombre', 'id');
        $this->layout->operadores_list = DB::table('operadores_list')->orderBy('nombre')->lists('nombre', 'id');
        $this->layout->unidades_list = DB::table('unidades_list')->orderBy('unidad')->lists('unidad', 'id');
        $this->layout->breadcrumb  = array(
            array(
                        'title' => 'Inicio',
                        'link'  => '/',
                        'icon'  => 'fas fa-home'
                      ),
                      array(
                        'title'     => 'Asignación de Ruta',
                        'link'      => 'asignacion/crear',
                        'icon'      => 'fas fa-truck'
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
        $asignacion = new Asignacion();
        if (!$asignacion->validate($new)) {
            $errors = $asignacion->errors();
            return Redirect::route('newAsignacion')->withInput()->withErrors($errors);
        }
        $asignacion = Asignacion::create($new);
        return Redirect::route('listAsignacion')->with('success', 'Se ha agregado con éxito la Asignación de Ruta');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $this->layout              = View::make('sistema.asignacion.show');
        $this->layout->title       = 'Mi Ruta';
        $this->layout->asignacion  = Asignacion::find($id);
        $this->layout->combustibles = AsignacionCombustible::where('asignacion_id', $id)->get();
        $this->layout->user        = Sentry::getUser();
        $this->layout->admin       = Sentry::findGroupByName('Admin');
        $this->layout->operadores  = Sentry::findGroupByName('Operador');
        $this->layout->breadcrumb  = array(
          array(
                        'title' => 'Inicio',
                        'link'  => '/',
                        'icon'  => 'fas fa-home'
                      ),
                      array(
                        'title'     => 'Mi Ruta',
                        'link'      => 'asignacion/'.$id.'/show',
                        'icon'      => 'fas fa-truck'
                      ),
        );
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $asignacion = Asignacion::find($id);
        $asignacion->terminado = 'Ruta Finalizada';
        $asignacion->save();
        return Redirect::route('listAsignacion')->with('info', 'La Ruta ha sido Finalizada.');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $asignacion = Asignacion::find($id);
        $asignacion->terminado = 'Ruta Finalizada';
        $asignacion->save();
        return Redirect::to('sistema_ram')->with('info', 'La Ruta ha sido Finalizada.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $asignacion = Asignacion::destroy($id);
        return Redirect::route('listAsignacion')->with('info', 'Se ha Eliminado con éxito la Asignación de Ruta');
    }
}
