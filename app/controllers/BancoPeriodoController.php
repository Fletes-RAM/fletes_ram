<?php

class BancoPeriodoController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->layout        = View::make('catalogos.periodo.index');
        $this->layout->title = 'Periodo Bancario';

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
            array(
                    'title' => 'Inicio',
                    'link'  => '/',
                    'icon'  => 'fas fa-home'
            ),
            array(
                    'title' => 'Periodo Bancario',
                    'link'  => 'catalogos/periodo',
                    'icon'  => 'fas fa-calendar'
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
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
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
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id=1)
    {
        $periodo = BancoPeriodo::find($id);
        $bancos = Banco::all();

        foreach ($bancos as $banco) {
            $movimientos = BancoMovSum::where('periodo', $periodo->periodo)->where('bancos_id', $banco->id)->first();
            if (!isset($movimientos)) {
                $total = 0;
            } else {
                $total = $movimientos->total;
            }
            //dump($total);
            $saldo = DB::table('bancos_saldos')->where('periodo', $periodo->periodo)->where('bancos_id', $banco->id)->first();
            if (!isset($saldo)) {
                $saldo_inicial = 0;
            } else {
                $saldo_inicial              = $saldo->saldo_inicial;
                $saldo_antiguo              = BancoSaldo::find($saldo->id);
                $saldo_antiguo->saldo_final = $saldo_inicial + $total;
                $saldo_antiguo->cerrado     = 1;
                //dump($saldo_antiguo);
                $saldo_antiguo->save();
            }

            $saldo_nuevo = new BancoSaldo();
            $saldo_nuevo->bancos_id = $banco->id;
            $saldo_nuevo->periodo = Input::get('value');
            $saldo_nuevo->saldo_inicial = $saldo_inicial + $total;
            $saldo_nuevo->cerrado = 0;
            $saldo_nuevo->save();
            //dump($saldo_nuevo);
        }

        $periodo->periodo = Input::get('value');
        $periodo->save();
        return Redirect::route('listBancoPeriodo')->with('success', 'Se ha actualizado con éxito el periodo bancario');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
