<?php

class BancoCategoriaController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->layout = View::make('catalogos.banco_categoria.index');
        $this->layout->title = 'Categorías Presupuesto';

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
          array(
                    'title' => 'Inicio',
                    'link'  => '/',
                    'icon'  => 'fas fa-home'
          ),
          array(
                    'title' => 'Categorías Presupuesto',
                    'link'  => 'catalogos/categoria',
                    'icon'  => 'fas fa-list'
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
        $this->layout        = View::make('catalogos.banco_categoria.create');
        $this->layout->title = 'Categorías Presupuesto';

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
                array(
                  'title' => 'Inicio',
                  'link'  => '/',
                  'icon'  => 'fas fa-home'
                ),
                array(
                  'title' => 'Categorías Presupuesto',
                  'link'  => 'catalogos/categoria',
                  'icon'  => 'fas fa-list'
                ),
                array(
                  'title' => 'Nueva Categoría Bancaria',
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
        $categoria = new BancoCategoria();
        if (!$categoria->validate($new)) {
            $errors = $categoria->errors();
            return Redirect::route('newBancoCategoria')->withInput()->withErrors($errors);
        }
        $categoria = BancoCategoria::create(Input::all());
        return Redirect::route('listBancoCategoria')->with('success', 'Se ha agregado con éxito la Categoría Bancaria');
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
        $this->layout        = View::make('catalogos.banco_categoria.create');
        $this->layout->title = 'Categorías Presupuesto';
        $this->layout->categoria = BancoCategoria::find($id);

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
              array(
                'title' => 'Inicio',
                'link'  => '/',
                'icon'  => 'fas fa-home'
              ),
              array(
                'title' => 'Categorías Presupuesto',
                'link'  => 'catalogos/categoria',
                'icon'  => 'fas fa-list'
              ),
              array(
                'title' => 'Editar Categoría Bancaria',
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
        $categoria = BancoCategoria::find($id);
        if (!$categoria->validate(Input::all())) {
            $errors = $categoria->errors();
            return Redirect::route('putBancoCategoria', $id)->withInput()->withErrors($errors);
        }
        $categoria->update(Input::all());
        return Redirect::route('putBancoCategoria', $id)->with('success', 'Se ha actualizado con éxito la Categoría Bancaria');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //$categoria = BancoCategoria::destroy($id);
        return Redirect::route('listBancoCategoria')->with('info', 'Se ha eliminado la Categoría Bancaria');
    }
}
