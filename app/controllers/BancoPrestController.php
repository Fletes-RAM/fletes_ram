<?php

class BancoPrestController extends \BaseController //phpcs:ignore
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->layout           = View::make('sistema.prestamo.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->layout             = View::make('sistema.prestamo.create');
        $this->layout->title      = 'Prestamos';
        $this->layout->bancos     = DB::table('bancos_list')->lists('banco', 'id');
        $this->layout->categorias = BancoCategoria::lists('categoria', 'id');
        $this->layout->operadores = DB::table('operadores_list')->orderBy('nombre')->lists('nombre', 'id');
        $admins = Sentry::findGroupByName('AdminsSueldos');
        $adminis = Sentry::findAllUsersInGroup($admins);
        $add = [];
        foreach ($adminis as $admin) {
            $add[$admin->id] = $admin->first_name . ' ' . $admin->last_name;
        }
        $this->layout->administradores = $add;
        $this->layout->periodo    = DB::table('bancos_periodos')->find(1);

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
            array(
                            'title' => 'Inicio',
                            'link'  => '/',
                            'icon'  => 'fas fa-home'
            ),
            array(
                            'title' => 'Prestamos',
                            'link'  => 'prestamos',
                            'icon'  => 'fas fa-hand-holding-usd'
            ),
            array(
                    'title' => 'Nuevo Prestamo',
                    'link'  => '#',
                    'icon'  => 'fas fa-plus'
            )
        );
    }

    public function createsalario()
    {
        $this->layout             = View::make('sistema.prestamo.window');
        $this->layout->title      = 'Prestamos';
        $this->layout->bancos     = DB::table('bancos_list')->lists('banco', 'id');
        $this->layout->categorias = BancoCategoria::lists('categoria', 'id');
        $this->layout->operadores = DB::table('operadores_list')->where('id', Input::get('operador'))->lists('nombre', 'id'); //phpcs:ignore
        $admins = Sentry::findGroupByName('AdminsSueldos');
        $adminis = Sentry::findAllUsersInGroup($admins);
        $add = [];
        foreach ($adminis as $admin) {
            $add[$admin->id] = $admin->first_name . ' ' . $admin->last_name;
        }
        $this->layout->administradores = $add;
        $this->layout->periodo    = DB::table('bancos_periodos')->find(1);

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
            array(
                            'title' => 'Inicio',
                            'link'  => '/',
                            'icon'  => 'fas fa-home'
            ),
            array(
                            'title' => 'Prestamos',
                            'link'  => 'prestamos',
                            'icon'  => 'fas fa-hand-holding-usd'
            ),
            array(
                    'title' => 'Nuevo Prestamo',
                    'link'  => '#',
                    'icon'  => 'fas fa-plus'
            )
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
        $banco = new BancoPrest();
        if (!$banco->validate($new)) {
            $errors = $banco->errors();
            return Redirect::route('newBancoPrest')->withInput()->withErrors($errors);
        }

        $banco = BancoPrest::create(Input::all());
        return Redirect::route('listBancoMov')->with('success', 'Se ha agregado con éxito el nuevo Prestamo.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function storesalario()
    {
        //return dump(Input::all());
        $new = Input::all();
        $banco = new BancoPrest();
        if (!$banco->validate($new)) {
            $errors = $banco->errors();
            return Redirect::route('newBancoPrestSueldo')->withInput()->withErrors($errors);
        }

        $banco = BancoPrest::create(Input::all());

        $sc = Input::get('subcategoria_id');

        if ($sc == 48 || $sc == 106 || $sc == 74 || $sc == 86 || $sc == 107) {
            $movBanco                  = new BancoMov();
            $movBanco->bancos_id       = Input::get('bancos_id');
            $movBanco->periodo         = Input::get('periodo');
            $movBanco->categoria_id    = Input::get('categoria_id');
            $movBanco->subcategoria_id = $sc;
            $movBanco->movimiento      = Input::get('movimiento');
            $movBanco->folio           = Input::get('folio');
            $movBanco->fecha           = Input::get('fecha');
            $movBanco->tipo            = Input::get('tipo') * -1;
            $movBanco->cantidad        = Input::get('cantidad');
            $movBanco->observaciones   = 'Movimiento Automático';
            $movBanco->save();
        }
 
        return Redirect::route('listBancoPrestSueldo')->with('success', 'Se ha agregado con éxito el nuevo Prestamo.');
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
        BancoPrest::destroy($id);
        return Redirect::back()->with('info', 'Se ha eliminado el Prestamo.');
    }
}
