<?php

class CotizacionController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->layout        = View::make('sistema.cotizacion.index');
        $this->layout->title = 'Cotizaciones';


        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
      array(
                'title' => 'Inicio',
                'link'  => '/',
                'icon'  => 'fas fa-home'
      ),
      array(
                'title' => 'Cotizaciones',
                'link'  => 'cotizacion',
                'icon'  => 'fas fa-money-check-alt'
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
        $this->layout                   = View::make('sistema.cotizacion.create');
        $this->layout->title            = 'Cotizaciones';
        $this->layout->clientes_list    = Cliente::lists('cliente', 'id');
        $this->layout->ruta_list        = Ruta::lists('nombre', 'id');
        $this->layout->tipo_unidad_list = TipoUnidad::lists('tipo_de_unidad', 'id');
        $this->layout->combustibles     = Combustible::lists('combustible', 'costo');

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
      array(
                'title' => 'Inicio',
                'link'  => '/',
                'icon'  => 'fas fa-home'
      ),
      array(
                'title' => 'Cotizaciones',
                'link'  => 'cotizacion',
                'icon'  => 'fas fa-money-check-alt'
      ),
      array(
                'title' => 'Nueva Cotización',
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
        //return dump(Input::all());
        $new = Input::all();
        $cotizacion = new Cotizacion();
        $propuesta = Input::get('propuesta');

        if (!$cotizacion->validate($new)) {
            $errors = $cotizacion->errors();
            return Redirect::to('cotizacion/crear')->withErrors($errors)->withInput();
        }
        $cotizacion->cliente_id = Input::get('cliente_id');
        $cotizacion->ruta_id = Input::get('ruta_id');
        $cotizacion->tipo_de_unidad_id = Input::get('tipo_de_unidad_id');
        $cotizacion->rendimiento_id = Input::get('rendimiento_id');
        $cotizacion->tot_km = Input::get('tot_km');
        $cotizacion->costo_combustible = Input::get('costo_combustible');
        $cotizacion->propuesta = Input::get('propuesta');
        $cotizacion->utilidad = $propuesta * (Input::get('utilidad') / 100);
        $cotizacion->sueldo_ope = $propuesta * (Input::get('sueldo_ope') / 100);
        $cotizacion->gastos_admon = $propuesta * (Input::get('gastos_admon') / 100);
        $cotizacion->otros_gastos = Input::get('otros_gastos');
        $cotizacion->combustible = Input::get('combustible');
        $cotizacion->caseta = Input::get('caseta');
        $cotizacion->observaciones = Input::get('observaciones');
        if ($cotizacion->save()) {
            $cotizacion->folio = 'C-'.date('Ymd').'-'.sprintf("%06d", $cotizacion->id);
            $cotizacion->save();
            //dump($cotizacion->folio);
            return Redirect::route('showCotizacion', $cotizacion->id)->with('success', 'Se ha creado con éxito la cotización <b> '.$cotizacion->folio.'<b>');
        }
        return Redirect::route('newCotizacion')->withInput()->withErrors($errors);
    }

    public function show($id)
    {
        $this->layout        = View::make('sistema.cotizacion.show');
        $this->layout->title = 'Cotizaciones';
        $this->layout->cotizacion = Cotizacion::find($id);
        switch (date('n')) {
            case 1:
                $mes = 'Enero';
                break;
            case 2:
                $mes = 'Febrero';
                break;
            case 3:
                $mes = 'Marzo';
                break;
            case 4:
                $mes = 'Abril';
                break;
            case 5:
                $mes = 'Mayo';
                break;
            case 6:
                $mes = 'Junio';
                break;
            case 7:
                $mes = 'Julio';
                break;
            case 8:
                $mes = 'Agosto';
                break;
            case 9:
                $mes = 'Septiembre';
                break;
            case 10:
                $mes = 'Octubre';
                break;
            case 11:
                $mes = 'Noviembre';
                break;
            case 12:
                $mes = 'Diciembre';
                break;
        }

        $this->layout->dia  = date('d');
        $this->layout->mes  = $mes;
        $this->layout->year = date('Y');

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
      array(
                'title' => 'Inicio',
                'link'  => '/',
                'icon'  => 'fas fa-home'
      ),
      array(
                'title' => 'Cotizaciones',
                'link'  => 'cotizacion',
                'icon'  => 'fas fa-money-check-alt'
      ),
      array(
                'title' => 'Cotización '.$this->layout->cotizacion->folio,
                'link'  => '#',
                'icon'  => 'fas fa-plus'
      ),
    );
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show_pdf($id)
    {
        switch (date('n')) {
            case 1:
                $mes = 'Enero';
                break;
            case 2:
                $mes = 'Febrero';
                break;
            case 3:
                $mes = 'Marzo';
                break;
            case 4:
                $mes = 'Abril';
                break;
            case 5:
                $mes = 'Mayo';
                break;
            case 6:
                $mes = 'Junio';
                break;
            case 7:
                $mes = 'Julio';
                break;
            case 8:
                $mes = 'Agosto';
                break;
            case 9:
                $mes = 'Septiembre';
                break;
            case 10:
                $mes = 'Octubre';
                break;
            case 11:
                $mes = 'Noviembre';
                break;
            case 12:
                $mes = 'Diciembre';
                break;
        }
        $data = array(
            'cotizacion' => Cotizacion::find($id),
            'dia' => date('d'),
            'mes' => $mes,
            'year' => date('Y')
        );
        //return dump($data);
        
        $pdf = PDF::loadView('sistema.cotizacion.pdf', $data);

        return $pdf->setPaper('a4')->setOrientation('landscape')->setWarnings(false)->save('myfile.pdf');

        //return dump($pdf);
        //return $pdf->download('test.pdf');


return $pdf->stream("cotizacion.pdf", array('attachment' => 1));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function send($id)
    {
        switch (date('n')) {
            case 1:
                $mes = 'Enero';
                break;
            case 2:
                $mes = 'Febrero';
                break;
            case 3:
                $mes = 'Marzo';
                break;
            case 4:
                $mes = 'Abril';
                break;
            case 5:
                $mes = 'Mayo';
                break;
            case 6:
                $mes = 'Junio';
                break;
            case 7:
                $mes = 'Julio';
                break;
            case 8:
                $mes = 'Agosto';
                break;
            case 9:
                $mes = 'Septiembre';
                break;
            case 10:
                $mes = 'Octubre';
                break;
            case 11:
                $mes = 'Noviembre';
                break;
            case 12:
                $mes = 'Diciembre';
                break;
        }
        $data = array(
            'cotizacion' => Cotizacion::find($id),
            'dia' => date('d'),
            'mes' => $mes,
            'year' => date('Y')
        );
        $dia        = date('d');
        $mes        = $mes;
        $year       = date('Y');
        $cotizacion = Cotizacion::find($id);
        //return dump($data);
        $html = View::make('sistema.cotizacion.pdf', compact('dia', 'mes', 'year', 'cotizacion'));
        $outputName = str_random(10);
        $date = date('Y-m-d');
        define('COTIZACIONES_DIR', 'uploads');
        $pdfPath = COTIZACIONES_DIR.'/'.$outputName.'.pdf';
        if (!is_dir(COTIZACIONES_DIR)) {
            mkdir(COTIZACIONES_DIR, 755, true);
        }
        //return dump(COTIZACIONES_DIR);
        $pdf = PDF::loadView('sistema.cotizacion.pdf', $data)->save($pdfPath);
        //File::put($pdfPath, PDF::loadView('sistema.cotizacion.pdf', $data));
        Mail::send('emails.pdf', $data, function ($message) use ($pdfPath,$cotizacion) {
            $message->from('cotizaciones@fletesram.com', 'Fletes RAM');
            $message->subject('Cotización Fletes RAM');
            $message->to($cotizacion->cliente->email);
            $message->cc('cotizaciones@fletesram.com');
            $message->cc('clecona@fletesram.com');
            $message->attach($pdfPath, array('as'=>'Cotizacion.pdf'));
        });

        return Redirect::route('listCotizacion')->with('success', 'Se ha enviado la cotización con éxito.');
    }

    public function edit($id)
    {
      $this->layout                   = View::make('sistema.cotizacion.create');
      $this->layout->title            = 'Editar Cotización';
      $this->layout->clientes_list    = Cliente::lists('cliente', 'id');
      $this->layout->ruta_list        = Ruta::lists('nombre', 'id');
      $this->layout->tipo_unidad_list = TipoUnidad::lists('tipo_de_unidad', 'id');
      $this->layout->combustibles     = Combustible::lists('combustible', 'costo');
      $this->layout->cotizacion       = Cotizacion::find($id);
      $this->layout->rendimiento_list = Rendimiento::where('tipo_de_unidad_id',$this->layout->cotizacion->tipo_de_unidad_id)->lists('rendimiento','id');

      // add breadcrumb to current page
      $this->layout->breadcrumb = array(
                    array(
          'title' => 'Inicio',
          'link'  => '/',
          'icon'  => 'fas fa-home'
                    ),
                    array(
          'title' => 'Editar Cotización',
          'link'  => 'cotizacion/'.$id.'/editar',
          'icon'  => 'fas fa-edit'
                    ),
                  );
    }

    public function update($id)
    {
        $data       = Input::all();
        $cotizacion = Cotizacion::find($id);
        $propuesta  = Input::get('propuesta');

        if (!$cotizacion->validate($data)) {
            $errors = $cotizacion->errors();
            return Redirect::to('cotizacion/'.$id.'/editar')->withErrors($errors)->withInput();
        }
        $cotizacion->cliente_id        = Input::get('cliente_id');
        $cotizacion->ruta_id           = Input::get('ruta_id');
        $cotizacion->tipo_de_unidad_id = Input::get('tipo_de_unidad_id');
        $cotizacion->rendimiento_id    = Input::get('rendimiento_id');
        $cotizacion->tot_km            = Input::get('tot_km');
        $cotizacion->costo_combustible = Input::get('costo_combustible');
        $cotizacion->propuesta         = Input::get('propuesta');
        $cotizacion->utilidad          = $propuesta * (Input::get('utilidad') / 100);
        $cotizacion->sueldo_ope        = $propuesta * (Input::get('sueldo_ope') / 100);
        $cotizacion->gastos_admon      = $propuesta * (Input::get('gastos_admon') / 100);
        $cotizacion->otros_gastos      = Input::get('otros_gastos');
        $cotizacion->combustible       = Input::get('combustible');
        $cotizacion->caseta            = Input::get('caseta');
        $cotizacion->observaciones     = Input::get('observaciones');
        if ($cotizacion->save()) {
            return Redirect::route('showCotizacion', $cotizacion->id)->with('success', 'Se ha actualizado con éxito la cotización <b> '.$cotizacion->folio.'<b>');
        }
        return Redirect::to('cotizacion/'.$id.'/editar')->withErrors($errors)->withInput();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $cotizacion = Cotizacion::destroy($id);
        return Redirect::route('listCotizacion')->with('info', 'Se ha eliminado la Cotización');
    }
}
