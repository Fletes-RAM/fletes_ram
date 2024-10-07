<?php

class RutaController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->layout        = View::make('sistema.ruta.index');
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
                'link'  => 'ruta',
                'icon'  => 'fas fa-road'
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
        $this->layout        = View::make('sistema.ruta.create');
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
                'link'  => 'ruta',
                'icon'  => 'fas fa-road'
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
        $ruta = new Ruta();
        if (!$ruta->validate($new)) {
            $errors = $ruta->errors();
            return Redirect::route('newRuta')->withInput()->withErrors($errors);
        }

        $kilometros          = 0;
        $ruta->nombre        = Input::get('nombre');
        $ruta->total_km      = $kilometros;
        $ruta->observaciones = Input::get('observaciones');

        if ($ruta->save()) {
            $estado         = Input::get('estado');
            $origen         = Input::get('del_mun');
            $estado_destino = Input::get('estado_destino');
            $destino        = Input::get('del_mun_destino');
            $km             = Input::get('total_km');
            foreach (Input::get('estado') as $key => $val) {
                $new_detalle                 = new RutaDetalle();
                $new_detalle->nombre_id      = $ruta->id;
                $new_detalle->estado         = $val;
                $new_detalle->origen         = $origen[$key];
                $new_detalle->estado_destino = $estado_destino[$key];
                $new_detalle->destino        = $destino[$key];
                $new_detalle->km             = $km[$key];
                $kilometros                  = $kilometros + $km[$key];
                $new_detalle->save();
            }
            $ruta->total_km = $kilometros;
            if ($ruta->save()) {
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
        $this->layout = View::make('sistema.ruta.detalle');
        $this->layout->ruta = Ruta::find($id);
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
