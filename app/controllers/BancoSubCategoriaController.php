<?php

class BancoSubCategoriaController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->layout = View::make('catalogos.banco_subcategoria.index');
        $this->layout->title = 'Subcategorías Presupuesto';

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
          array(
                    'title' => 'Inicio',
                    'link'  => '/',
                    'icon'  => 'fas fa-home'
          ),
          array(
                    'title' => 'Subcategorías Presupuesto',
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
        $this->layout             = View::make('catalogos.banco_subcategoria.create');
        $this->layout->title      = 'Subcategorías Presupuesto';
        $this->layout->categorias = BancoCategoria::lists('categoria', 'id');

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
                array(
                  'title' => 'Inicio',
                  'link'  => '/',
                  'icon'  => 'fas fa-home'
                ),
                array(
                  'title' => 'Subcategorías Presupuesto',
                  'link'  => 'catalogos/categoria',
                  'icon'  => 'fas fa-list'
                ),
                array(
                  'title' => 'Nueva Subcategoria Bancaria',
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
        $subcategoria = new BancoSubCategoria();
        if (!$subcategoria->validate($new)) {
            $errors = $subcategoria->errors();
            return Redirect::route('newBancoSubCategoria')->withInput()->withErrors($errors);
        }
        $subcategoria = BancoSubCategoria::create(Input::all());
        return Redirect::route('listBancoSubCategoria')->with('success', 'Se ha agregado con éxito la Subcategoria Bancaria');
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
        $this->layout        = View::make('catalogos.banco_subcategoria.create');
        $this->layout->title = 'Subcategorías Presupuesto';
        $this->layout->subcategoria = BancoSubCategoria::find($id);
        $this->layout->categorias = BancoCategoria::lists('categoria', 'id');

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
              array(
                'title' => 'Inicio',
                'link'  => '/',
                'icon'  => 'fas fa-home'
              ),
              array(
                'title' => 'Subcategorías Presupuesto',
                'link'  => 'catalogos/categoria',
                'icon'  => 'fas fa-list'
              ),
              array(
                'title' => 'Editar Subcategoria Bancaria',
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
        $subcategoria = BancoSubCategoria::find($id);
        if (!$subcategoria->validate(Input::all())) {
            $errors = $subcategoria->errors();
            return Redirect::route('putBancoSubCategoria', $id)->withInput()->withErrors($errors);
        }
        $subcategoria->update(Input::all());
        return Redirect::route('putBancoSubCategoria', $id)->with('success', 'Se ha actualizado con éxito la Subcategoria Bancaria');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //$subcategoria = BancoSubCategoria::destroy($id);
        return Redirect::route('listBancoSubCategoria')->with('info', 'Se ha eliminado la Subcategoria Bancaria');
    }
}
