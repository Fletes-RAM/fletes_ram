<?php

class BancoMovController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->layout        = View::make('sistema.movimiento.index');
        $this->layout->title = 'Movimientos Bancarios';

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
          array(
              'title' => 'Inicio',
              'link'  => '/',
              'icon'  => 'fas fa-home'
          ),
          array(
              'title' => 'Movimientos Bancarios',
              'link'  => 'movimiento',
              'icon'  => 'fas fa-money-bill-alt'
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
        $this->layout                = View::make('sistema.movimiento.create');
        $this->layout->title         = 'Movimientos Bancarios';
        $this->layout->bancos        = DB::table('bancos_list')->orderBy('banco')->lists('banco', 'id');
        $this->layout->categorias    = BancoCategoria::lists('categoria', 'id');
        $this->layout->subcategorias = BancoSubCategoria::lists('subcategoria', 'id');
        $this->layout->periodo       = DB::table('bancos_periodos')->find(1);

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
          array(
                  'title' => 'Inicio',
                  'link'  => '/',
                  'icon'  => 'fas fa-home'
          ),
          array(
                  'title' => 'Movimientos Bancarios',
                  'link'  => 'movimiento',
                  'icon'  => 'fas fa-money-bill-alt'
          ),
          array(
              'title' => 'Nuevo Movimiento Bancario',
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
        $banco = new BancoMov();
        if (!$banco->validate($new)) {
            $errors = $banco->errors();
            return Redirect::route('newBancoMov')->withInput()->withErrors($errors);
        }

        $banco = BancoMov::create(Input::all());
        return Redirect::route('listBancoMov')->with('success', 'Se ha agregado con éxito el nuevo Movimiento Bancario.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $this->layout              = View::make('sistema.movimiento.detalle');
        $this->layout->title       = 'Detalle Movimientos Bancarios';
        $this->layout->periodo     = BancoPeriodo::find(1);
        $this->layout->banco       = Banco::find($id);
        $this->layout->saldos      = BancoSaldo::where('periodo', $this->layout->periodo->periodo)->where('bancos_id', $this->layout->banco->id)->first();
        $this->layout->movimientos = BancoMov::where('periodo', $this->layout->periodo->periodo)->where('bancos_id', $this->layout->banco->id)->get();
        $this->layout->sumatoria   = DB::table('bancos_movimientos_sum')
                                       ->select(DB::raw('periodo,bancos_id,sum(total) as total'))
                                       ->where('periodo', $this->layout->periodo->periodo)
                                       ->where('bancos_id', $this->layout->banco->id)->first();
        //BancoMovSum::where('periodo', $this->layout->periodo->periodo)->where('bancos_id', $this->layout->banco->id)->first();
        $this->layout->prestamos   = BancoPrest::where('periodo', $this->layout->periodo->periodo)->where('bancos_id', $this->layout->banco->id)->get();
        $this->layout->detalles    = BancoDetalle::where('periodo', $this->layout->periodo->periodo)->where('bancos_id', $this->layout->banco->id)->get();

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
          array(
                  'title' => 'Inicio',
                  'link'  => '/',
                  'icon'  => 'fas fa-home'
          ),
          array(
                  'title' => 'Movimientos Bancarios',
                  'link'  => 'movimiento',
                  'icon'  => 'fas fa-money-bill-alt'
          ),
          array(
              'title' => 'Detalles Movimiento Bancario',
              'link'  => '#',
              'icon'  => 'fas fa-list'
          )
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
      list($tipo,$i) = explode('-',$id);
      $tipo = $tipo;
      $i = $i;
      if ($tipo == 'm') {
        $ticket = BancoMov::destroy($i);
      }
      if ($tipo == 'p') {
        $ticket = BancoPrest::destroy($i);
      }
      return Redirect::back()->with('info','Se ha eliminado el movimiento bancario.');
    }
}
