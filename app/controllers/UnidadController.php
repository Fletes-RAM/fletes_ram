<?php

class UnidadController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->layout        = View::make('catalogos.unidad.index');
        $this->layout->title = 'Unidades';


        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
      array(
                'title' => 'Inicio',
                'link'  => '/',
                'icon'  => 'fas fa-home'
      ),
      array(
                'title' => 'Unidades',
                'link'  => 'catalogos/unidad',
                'icon'  => 'fas fa-truck'
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
        $this->layout                  = View::make('catalogos.unidad.create');
        $this->layout->title           = 'Unidades';
        $this->layout->tipounidad_list = TipoUnidad::lists('tipo_de_unidad', 'id');

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
      array(
                'title' => 'Inicio',
                'link'  => '/',
                'icon'  => 'fas fa-home'
      ),
      array(
                'title' => 'Unidades',
                'link'  => 'catalogos/unidad',
                'icon'  => 'fas fa-truck'
      ),
      array(
                'title' => 'Nueva Unidad',
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
        $new        = Input::all();
        $unidad = new Unidad();
        if (!$unidad->validate($new)) {
            $errors = $unidad->errors();
            return Redirect::route('newUnidad')->withInput()->withErrors($errors);
        }

        $unidad->unidad            = Input::get('unidad');
        $unidad->tipo_de_unidad_id = Input::get('tipo_de_unidad_id');
        $unidad->placas            = Input::get('placas');
        $unidad->serie             = Input::get('serie');
        $unidad->poliza            = Input::get('poliza');
        $unidad->aseguradora       = Input::get('aseguradora');
        $unidad->km_inicial        = Input::get('km_inicial');
        $unidad->vigencia          = Input::get('vigencia');
        $unidad->observaciones     = Input::get('observaciones');
        if ($unidad->save()) {
            return Redirect::route('listUnidad')->with('success', 'Se ha agregado con éxito la nueva Unidad.');
        }
        return Redirect::route('newUnidad')->withInput()->withErrors($errors);
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
        $this->layout                  = View::make('catalogos.unidad.create');
        $this->layout->title           = 'Unidades';
        $this->layout->tipounidad_list = TipoUnidad::lists('tipo_de_unidad', 'id');
        $this->layout->unidad          = Unidad::find($id);

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
      array(
                'title' => 'Inicio',
                'link'  => '/',
                'icon'  => 'fas fa-home'
      ),
      array(
                'title' => 'Unidades',
                'link'  => 'catalogos/unidad',
                'icon'  => 'fas fa-truck'
      ),
      array(
                'title' => 'Editar Unidad',
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
        $unidad = Unidad::find($id);
        if (!$unidad->validate(Input::all(), $id)) {
            $errors = $unidad->errors();
            return Redirect::route('putUnidad', $id)->withInput()->withErrors($errors);
        }

        $unidad->unidad            = Input::get('unidad');
        $unidad->tipo_de_unidad_id = Input::get('tipo_de_unidad_id');
        $unidad->placas            = Input::get('placas');
        $unidad->serie             = Input::get('serie');
        $unidad->poliza            = Input::get('poliza');
        $unidad->aseguradora       = Input::get('aseguradora');
        $unidad->km_inicial        = Input::get('km_inicial');
        $unidad->vigencia          = Input::get('vigencia');
        $unidad->observaciones     = Input::get('observaciones');
        if ($unidad->save()) {
            return Redirect::route('listUnidad')->with('success', 'Se han guardado con éxito los cambios	.');
        }
        return Redirect::route('putUnidad', $id)->withInput()->withErrors($errors);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $unidad = Unidad::destroy($id);
        return Redirect::route('listUnidad')->with('info', 'Se ha eliminado la Unidad.');
    }
}
