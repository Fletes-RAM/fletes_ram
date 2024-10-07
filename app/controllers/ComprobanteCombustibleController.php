<?php

class ComprobanteCombustibleController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->layout           = View::make('sistema.comprobantes.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->layout = View::make('sistema.comprobantes.create');
        $this->layout->comprobantes_combustibles = ComprobanteCombustibleVista::where('user_id', Input::get('operador'))->whereRaw('id not in (select combustible_id from ram_comprobantes_combustible)')->orderBy('fecha')->get();
        $this->layout->operador = Input::get('operador');
        $this->layout->fecha1 = Input::get('fecha1');
        $this->layout->fecha2 = Input::get('fecha2');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $operador = Input::get('user_id');
        $fecha1 = Input::get('fecha1');
        $fecha2 = Input::get('fecha2');
        $combustible_id = Input::get('combustible_id');

        if (!Input::has('combustible_id')) {
            return Redirect::route('comprobante_combustible.create', ['operador'=>$operador,'fecha1'=>$fecha1,'fecha2'=>$fecha2])->withInput()->with('error', 'No ha capturado ningun dato');
        }

        foreach (Input::get('combustible_id') as $key => $value) {
            $data = [
            'combustible_id' => $combustible_id[$key],
            'user_id' => $operador
          ];

            $comprobante_combustible = new ComprobanteCombustible();
            $comprobante_combustible->create($data);
        }

        return Redirect::route('comprobante_combustible.index')->with('success', 'Cambios Guardados. Hacer click en el boton cerrar para refrescar cambios');
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
        $combustible = ComprobanteCombustible::where('combustible_id',$id)->delete();
        if ($combustible != 0) {
          return Redirect::back()->with('info','Se ha eliminado el comprobante de combustible.');
        }
        return Redirect::back()->with('error','Ha ocurrido un error.');
    }
}
