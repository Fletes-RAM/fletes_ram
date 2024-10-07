<?php

class ControlController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->layout           = View::make('sistema.control.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->layout           = View::make('sistema.control.create');
        $this->layout->title    = '';
        $this->layout->origenes = Origen::orderBy('origen')->lists('origen','id');

        // add breadcrumb to current page
        $this->layout->breadcrumb = array(
                    array(
          'title' => 'Inicio',
          'link'  => '/',
          'icon'  => 'fas fa-home'
                    ),
                    array(
          'title' => '',
          'link'  => '',
          'icon'  => 'fas fa-'
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
        $user_id        = Input::get('user_id');
        $porcentaje     = Input::get('porcentaje');
        $fecha          = Input::get('fecha');
        $cont_vehicular = Input::get('control_vehicular');
        $orig           = Input::get('origen');
        $toneladas      = Input::get('toneladas');
        $tarifa         = Input::get('tarifa');
        $cantidad       = Input::get('cantidad');
        $iva            = Input::get('iva');

        if (!Input::has('fecha')) {
            return Redirect::route('control.create')->withInput()->with('error', 'No ha capturado ningun dato');
        }

        foreach (Input::get('fecha') as $key => $value) {
            $data = [
              'user_id' => $user_id,
              'porcentaje' => $porcentaje,
              'fecha' => $fecha[$key],
              'control_vehicular' => $cont_vehicular[$key],
              'origen' => $orig[$key],
              'toneladas' => $toneladas[$key],
              'cantidad' => $cantidad[$key],
              'tarifa' => $tarifa[$key]
            ];
            $control_vehicular = new ControlVehicular();
            if (!$control_vehicular->validate($data)) {
                $errors = $control_vehicular->errors();
                //return dump($data);
                return Redirect::route('control.create')->withInput()->withErrors($errors);
            }
        }

        foreach (Input::get('fecha') as $key => $value) {
            if (isset($iva[$key])) {
                $cant = $cantidad[$key] / 1.16;
                $iva = .16;
            } else {
                $cant = $cantidad[$key];
                $iva = 0;
            }
            $data = [
              'user_id' => $user_id,
              'porcentaje' => $porcentaje,
              'fecha' => $fecha[$key],
              'control_vehicular' => $cont_vehicular[$key],
              'origen' => $orig[$key],
              'toneladas' => $toneladas[$key],
              'cantidad' => $cant,
              'tarifa' => $tarifa[$key],
              'iva' => $iva
            ];
            $control_vehicular = new ControlVehicular();
            $control_vehicular->create($data);
        }

        return Redirect::route('control.index')->with('success', 'Cambios Guardados. Hacer click en el boton cerrar para refrescar cambios');
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
      $controles = array();
      foreach (Input::get('comprobante_id') as $key => $value) {
        $controles[] = ControlVehicular::find($value);
      }
      $this->layout                = View::make('sistema.factura.createControl');
      $this->layout->title         = 'Asignar Factura';
      $this->layout->control       = ControlVehicular::find($id);
      $this->layout->controles     = $controles;
      $this->layout->clientes_list = Cliente::orderBy('cliente','ASC')->lists('cliente', 'id');
    //   return dump($this->layout->controles);

      // add breadcrumb to current page
      $this->layout->breadcrumb = array(
                  array(
        'title' => 'Inicio',
        'link'  => '/',
        'icon'  => 'fas fa-home'
                  ),
                  array(
        'title' => 'Asignar Factura',
        'link'  => 'factura/create',
        'icon'  => 'fas fa-file-alt'
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
      $controles = array();
      Input::merge(['control_id'=>0]);
      $new = Input::all();
      $factura_control = new FacturaControl();
      if(!$factura_control->validate($new)) {
        $errors = $factura_control->errors();
        return Redirect::back()->withInput()->withErrors($errors);
      }


      $factura_control = FacturaControl::create($new);

      foreach (Input::get('ctrls') as $key => $value) {
        $control = ControlVehicular::find($value);
        $control->factura_id = $factura_control->id;
        $control->save();
      }
      

      return Redirect::route('factura.index')->with('success','Se ha creado la Factura con éxito.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
      ControlVehicular::destroy($id);
      Session::flash('info','Se ha borrado el Control Vehicular.');
      return Redirect::back();
    }
}
