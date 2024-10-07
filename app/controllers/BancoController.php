<?php

class BancoController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->layout        = View::make('catalogos.banco.index');
        $this->layout->title = 'Bancos';

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
          array(
                    'title' => 'Inicio',
                    'link'  => '/',
                    'icon'  => 'fas fa-home'
          ),
          array(
                    'title' => 'Bancos',
                    'link'  => 'catalogos/banco',
                    'icon'  => 'fas fa-piggy-bank'
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
        $this->layout        = View::make('catalogos.banco.create');
        $this->layout->title = 'Bancos';

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
          array(
                    'title' => 'Inicio',
                    'link'  => '/',
                    'icon'  => 'fas fa-home'
          ),
          array(
                    'title' => 'Bancos',
                    'link'  => 'catalogos/banco',
                    'icon'  => 'fas fa-piggy-bank'
          ),
                array(
                    'title' => 'Nuevo Banco',
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
        $banco = new Banco();
        if (!$banco->validate($new)) {
            $errors = $banco->errors();
            return Redirect::route('newBanco')->withInput()->withErrors($errors);
        }

        $banco = Banco::create(Input::all());
        return Redirect::route('listBanco')->with('success', 'Se ha agregado con éxito el nuevo Banco.');
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
        $this->layout        = View::make('catalogos.banco.create');
        $this->layout->title = 'Bancos';
        $this->layout->banco = Banco::find($id);

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
        array(
                  'title' => 'Inicio',
                  'link'  => '/',
                  'icon'  => 'fas fa-home'
        ),
        array(
                  'title' => 'Bancos',
                  'link'  => 'catalogos/banco',
                  'icon'  => 'fas fa-piggy-bank'
        ),
              array(
                  'title' => 'Nuevo Banco',
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
        $banco = Banco::find($id);
        if (!$banco->validate(Input::all(), $id)) {
            $errors = $banco->errors();
            return Redirect::route('putBanco', $id)->withInput()->withErrors($errors);
        }
        $banco->update(Input::all());
        return Redirect::route('putBanco', $id)->with('success', 'Se ha actualizado con éxito el Banco.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $banco = Banco::destroy($id);
        return Redirect::route('listBanco')->with('info', 'Se ha Eliminado con éxito el Banco.');
    }
}
