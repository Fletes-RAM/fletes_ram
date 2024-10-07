<?php

class TipoUnidadController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->layout        = View::make('catalogos.tipounidad.index');
        $this->layout->title = 'Tipo de Unidad';


        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
      array(
                'title' => 'Inicio',
                'link'  => '/',
                'icon'  => 'fas fa-home'
      ),
      array(
                'title' => 'Tipo de Unidad',
                'link'  => 'catalogos/tipounidad',
                'icon'  => 'fas fa-truck-moving'
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
        $this->layout        = View::make('catalogos.tipounidad.create');
        $this->layout->title = 'Tipo de Unidad';


        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
      array(
                'title' => 'Inicio',
                'link'  => '/',
                'icon'  => 'fas fa-home'
      ),
      array(
                'title' => 'Tipo de Unidad',
                'link'  => 'catalogos/tipodeunidad',
                'icon'  => 'fas fa-truck-moving'
      ),
      array(
                'title' => 'Nuevo Tipo de Unidad',
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
        $new          = Input::all();
        $tipodeunidad = new TipoUnidad();
        if (!$tipodeunidad->validate($new)) {
            $errors = $tipodeunidad->errors();
            return Redirect::route('newTipoUnidad')->withInput()->withErrors($errors);
        }

        $tipodeunidad->tipo_de_unidad = Input::get('tipo_de_unidad');
        $tipodeunidad->porcentaje     = Input::get('porcentaje');
        $tipodeunidad->observaciones  = Input::get('observaciones');
        if ($tipodeunidad->save()) {
            return Redirect::route('listTipoUnidad')->with('success', 'Se ha agregado con éxito el nuevo Tipo de Unidad.');
        }
        return Redirect::route('newTipoUnidad')->withInput()->withErrors($errors);
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
        $this->layout        = View::make('catalogos.tipounidad.create');
        $this->layout->title = 'Tipo de Unidad';
        $this->layout->tipodeunidad = TipoUnidad::find($id);


        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
      array(
                'title' => 'Inicio',
                'link'  => '/',
                'icon'  => 'fas fa-home'
      ),
      array(
                'title' => 'Tipo de Unidad',
                'link'  => 'catalogos/tipodeunidad',
                'icon'  => 'fas fa-truck-moving'
      ),
      array(
                'title' => 'Editar Tipo de Unidad',
                'link'  => '#',
                'icon'  => 'fas fa-pencil-alt'
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
        $tipodeunidad = TipoUnidad::find($id);
        if (!$tipodeunidad->validate(Input::all())) {
            $errors = $tipodeunidad->errors();
            return Redirect::route('putTipoUnidad', $id)->withInput()->withErrors($errors);
        }

        $tipodeunidad->tipo_de_unidad    = Input::get('tipo_de_unidad');
        $tipodeunidad->porcentaje        = Input::get('porcentaje');
        $tipodeunidad->observaciones = Input::get('observaciones');
        if ($tipodeunidad->save()) {
            return Redirect::route('listTipoUnidad')->with('success', 'Se han guardado con éxito los cambios	.');
        }
        return Redirect::route('putTipoUnidad', $id)->withInput()->withErrors($errors);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $tipodeunidad = TipoUnidad::destroy($id);
        return Redirect::route('listTipoUnidad')->with('info', 'Se ha eliminado el Tipo de Unidad');
    }
}
