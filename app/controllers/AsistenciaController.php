<?php

class AsistenciaController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $this->layout        = View::make('sistema.asistencias.index');
        $this->layout->title = 'Asistencias';
        $admins              = Sentry::findGroupByName('AdminsSueldos');
        $adminis             = Sentry::findAllUsersInGroup($admins);
        $add                 = [];
        $opes              = Sentry::findGroupByName('Operador');
        $operadores             = Sentry::findAllUsersInGroup($opes);
        $opes              = Sentry::findGroupByName('OperadorEsp');
        $operadoresEsp             = Sentry::findAllUsersInGroup($opes);
        $collection1 = $adminis->merge($operadores);
        $trabajadores = $collection1->merge($operadoresEsp);

        // $this->layout->trabajadores = $trabajadores->all();
        $this->layout->trabajadores = $adminis;


        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
            array(
                'title' => 'Inicio',
                'link'  => '/',
                'icon'  => 'fas fa-home'
            ),
            array(
                'title' => 'Asistencias',
                'link'  => 'asistencias',
                'icon'  => 'fas fa-check'
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
        $data = Input::get('trabajador');
        //dump($data);
        // foreach (Input::get('trabajador1') as $key => $value) {
        //     dump($value);
        // }
        $hoy = date('Y-m-d');
        // $hoy = '2023-05-12';
        $asis = Asistencia::where('asistencia',$hoy);
        $cuenta=$asis->count();
        if ($cuenta == 0){
            foreach (Input::get('trabajador1') as $key => $trabajador){
                $asistencia = new Asistencia();
                $asistencia->user_id = $data[$key];
                $asistencia->asistencia = $hoy;
                $asistencia->tipo = $trabajador;
                $asistencia->save();
            }
            return Redirect::route('asistencia.index')->with('success', 'Se ha guardado con éxito la asistencia del día de hoy.');
        }
        return Redirect::route('asistencia.index')->with('info', 'Ya ha sido agregada la asistencia del día de hoy');
    }


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
    public function show($id)
    {
        $this->layout              = View::make('sistema.asistencias.show');
        $this->layout->title       = 'Reporte Asistencias';
        $this->layout->fecha1 = Input::get('fecha1') . ' 00:00:00';
        $this->layout->fecha2 = Input::get('fecha2') . ' 23:59:59';
        $this->layout->asistencias = Asistencia::whereBetween('asistencia',[$this->layout->fecha1,$this->layout->fecha2])->get();
        // $this->layout->asistencias = Asistencia::all();
        // dump(Asistencia::all());

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
            array(
                'title' => 'Inicio',
                'link'  => '/',
                'icon'  => 'fas fa-home'
            ),
            array(
                'title' => 'Reporte Asistencias',
                'link'  => 'asistencias',
                'icon'  => 'fas fa-check'
            ),
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
		//
	}


}
