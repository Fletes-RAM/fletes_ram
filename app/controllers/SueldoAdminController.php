<?php

class SueldoAdminController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->layout        = View::make('sistema.sueldosAdmin.index');
        $this->layout->title = 'Sueldos Administradores';
        $admins = Sentry::findGroupByName('AdminsSueldos');
        $adminis = Sentry::findAllUsersInGroup($admins);
        $add = [];
        foreach ($adminis as $admin) {
            $add[$admin->id] = $admin->first_name . ' ' . $admin->last_name;
        }
        $this->layout->administradores = $adminis;


        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
          array(
                    'title' => 'Inicio',
                    'link'  => '/',
                    'icon'  => 'fas fa-home'
          ),
          array(
                    'title' => 'Sueldos Administradores',
                    'link'  => 'sueldosAdmin',
                    'icon'  => 'fas fa-coins'
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
        $this->layout        = View::make('sistema.sueldosAdmin.create');
        $this->layout->title = 'Rutas';

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
      array(
                'title' => 'Inicio',
                'link'  => '/',
                'icon'  => 'fas fa-home'
      ),
      array(
                'title' => 'Rutas',
                'link'  => 'sueldosAdmin',
                'icon'  => 'fas fa-coins'
      ),
      array(
                'title' => 'Nueva Ruta',
                'link'  => '#',
                'icon'  => 'fas fa-plus'
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
        $sueldosAdmin = new Ruta();
        if (!$sueldosAdmin->validate($new)) {
            $errors = $sueldosAdmin->errors();
            return Redirect::route('newRuta')->withInput()->withErrors($errors);
        }

        $kilometros          = 0;
        $sueldosAdmin->nombre        = Input::get('nombre');
        $sueldosAdmin->total_km      = $kilometros;
        $sueldosAdmin->observaciones = Input::get('observaciones');

        if ($sueldosAdmin->save()) {
            $estado         = Input::get('estado');
            $origen         = Input::get('del_mun');
            $estado_destino = Input::get('estado_destino');
            $destino        = Input::get('del_mun_destino');
            $km             = Input::get('total_km');
            foreach (Input::get('estado') as $key => $val) {
                $new_detalle                 = new RutaDetalle();
                $new_detalle->nombre_id      = $sueldosAdmin->id;
                $new_detalle->estado         = $val;
                $new_detalle->origen         = $origen[$key];
                $new_detalle->estado_destino = $estado_destino[$key];
                $new_detalle->destino        = $destino[$key];
                $new_detalle->km             = $km[$key];
                $kilometros                  = $kilometros + $km[$key];
                $new_detalle->save();
            }
            $sueldosAdmin->total_km = $kilometros;
            if ($sueldosAdmin->save()) {
                return Redirect::route('listRuta')->with('success', 'Se ha agregado con éxito la nueva Ruta.');
            }
            return Redirect::route('newRuta')->withInput()->withErrors($errors);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $this->layout = View::make('sistema.sueldosAdmin.detalle');
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
        $rendimiento = Ruta::destroy($id);
        return Redirect::route('listRuta')->with('info', 'Se ha eliminado la Ruta');
    }
}
