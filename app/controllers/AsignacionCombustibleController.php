<?php

class AsignacionCombustibleController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
      //return dump(AsignacionEspecial::all());
        $this->layout                        = View::make('sistema.asignacioncombustible.index');
        $this->layout->title                 = 'Carga de Combustible SIN Asignación de Ruta';
        $this->layout->asignacionesepeciales = AsignacionEspecial::orderBy('created_at','desc')->limit(1000)->get();
        $this->layout->breadcrumb            = array(
          array(
            'title' => 'Inicio',
            'link'  => '/',
            'icon'  => 'fas fa-home'
          ),
          array(
            'title'     => 'Carga de Combustible SIN Asignación de Ruta',
            'link'      => 'sistema_ram',
            'icon'      => 'fas fa-gas-pump'
          ),
        );
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($id)
    {
        $this->layout                   = View::make('sistema.asignacioncombustible.create');
        $this->layout->title            = 'Carga de Combustible';
        $this->layout->gasolineras_list = Gasolinera::orderBy('gasolinera')->lists('gasolinera', 'id');
        $this->layout->breadcrumb       = array(
            array(
                        'title' => 'Inicio',
                        'link'  => '/',
                        'icon'  => 'fas fa-home'
                      ),
                      array(
                        'title'     => 'Carga de Combustible',
                        'link'      => 'asignacion/combustible/'.$id.'/crear',
                        'icon'      => 'fas fa-gas-pump'
                      ),
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($id)
    {
      //return dump(Input::all());
        $new = Input::all();
        $asignacioncombustible = new AsignacionCombustible();

        if (!$asignacioncombustible->validate($new)) {
            $errors = $asignacioncombustible->errors();
            return Redirect::route('newAsignacionCombustible', $id)->withInput()->withErrors($errors);
        }

        $asignacion  = Asignacion::find($id);
        $km_inicial  = $asignacion->unidad->km_inicial;
        $litros      = Input::get('litros');
        $precio      = Input::get('precio');
        $total       = $litros * $precio;
        $rendimiento = (Input::get('kilometraje') - $km_inicial) / $litros;


        Input::merge(['asignacion_id'=>$id]);
        Input::merge(['total'=>$total]);
        Input::merge(['rendimiento'=>$rendimiento]);

        $date = date('Y-m-d');
        $destinationPath = 'upl/' .$date.'/';

        if (Input::file('foto')!=null) {
            $file      = Input::file('foto');
            $filename  = Shuffle::generateRandomString().$file->getClientOriginalName();
            $file->move($destinationPath, $filename);
            $savedPath = $destinationPath . $filename;
            Input::merge(['foto_ticket'=>$savedPath]);
        }
        if (Input::file('foto2')!=null) {
            $file2      = Input::file('foto2');

            $filename2  = Shuffle::generateRandomString().$file2->getClientOriginalName();
            $file2->move($destinationPath, $filename2);
            $savedPath2 = $destinationPath . $filename2;
            Input::merge(['foto_tablero_antes'=>$savedPath2]);
        }

        if (Input::file('foto3')!=null) {
            $file3      = Input::file('foto3');
            $filename3  = Shuffle::generateRandomString().$file3->getClientOriginalName();
            $file3->move($destinationPath, $filename3);
            $savedPath3 = $destinationPath . $filename3;
            Input::merge(['foto_tablero_despues'=>$savedPath3]);
        }

        if (Input::file('foto4')!=null) {
            $file4      = Input::file('foto4');
            $filename4  = Shuffle::generateRandomString().$file4->getClientOriginalName();
            $file4->move($destinationPath, $filename4);
            $savedPath4 = $destinationPath . $filename4;
            Input::merge(['foto_tablero_km'=>$savedPath4]);
        }

        $unidad = Unidad::find($asignacion->unidad->id);
        $unidad->km_inicial = Input::get('kilometraje');

        //return dump($unidad->km_inicial);

        //return dump(Input::all());

        $asignacioncombustible->create(Input::all());
        $unidad->save();
        return Redirect::route('showAsignacion', $id)->with('success', 'Se ha guardado con éxito el ticket de combustible');
    }

    public function storeEsp()
    {
        $new = Input::all();
        $asignacionespecial = new AsignacionEspecial();

        if (!$asignacionespecial->validate($new)) {
            $errors = $asignacionespecial->errors();
            return Redirect::to('sistema_ram')->withInput()->withErrors($errors);
        }

        $unidad = Unidad::find(Input::get('unidad_id'));
        $km_inicial = $unidad->km_inicial;
        $litros = Input::get('litros');
        $precio = Input::get('precio');
        $total = $litros * $precio;
        $rendimiento = (Input::get('kilometraje') - $km_inicial) / $litros;


        Input::merge(['total'=>$total]);
        Input::merge(['rendimiento'=>$rendimiento]);

        $date = date('Y-m-d');
        $destinationPath = 'upl/' .$date.'/';

        if (Input::file('foto')!=null) {
            $file      = Input::file('foto');
            $filename  = Shuffle::generateRandomString().$file->getClientOriginalName();
            $file->move($destinationPath, $filename);
            $savedPath = $destinationPath . $filename;
            Input::merge(['foto_ticket'=>$savedPath]);
        }
        //$file      = Image::make($savedPath)->widen(400);
        //$file->save($savedPath, 60);

        if (Input::file('foto2')!=null) {
            $file2 = Input::file('foto2');
            $filename2 = Shuffle::generateRandomString().$file2->getClientOriginalName();
            $file2->move($destinationPath, $filename2);
            $savedPath2 = $destinationPath . $filename2;
            Input::merge(['foto_tablero_antes'=>$savedPath2]);
        }

        if (Input::file('foto3')!=null) {
            $file3 = Input::file('foto3');
            $filename3 = Shuffle::generateRandomString().$file3->getClientOriginalName();
            $file3->move($destinationPath, $filename3);
            $savedPath3 = $destinationPath . $filename3;
            Input::merge(['foto_tablero_despues'=>$savedPath3]);
        }

        if (Input::file('foto4')!=null) {
            $file4      = Input::file('foto4');
            $filename4  = Shuffle::generateRandomString().$file4->getClientOriginalName();
            $file4->move($destinationPath, $filename4);
            $savedPath4 = $destinationPath . $filename4;
            Input::merge(['foto_tablero_km'=>$savedPath4]);
        }

        $unidad->km_inicial = Input::get('kilometraje');

        //return dump($unidad->km_inicial);

        //return dump(Input::all());

        $asignacionespecial->create(Input::all());
        $unidad->save();
        return Redirect::route('prueba')->with('success', 'Se ha guardado con éxito el ticket de combustible');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $this->layout                = View::make('sistema.asignacioncombustible.show');
        $this->layout->title         = 'Carga de Combustible';
        $this->layout->combustible    = AsignacionEspecial::find($id);
        $this->layout->user          = Sentry::getUser();
        $this->layout->admin         = Sentry::findGroupByName('Admin');
        $this->layout->operadores    = Sentry::findGroupByName('Operador');
        $this->layout->operadoresesp = Sentry::findGroupByName('OperadorEsp');
        $this->layout->breadcrumb    = array(
        array(
                      'title' => 'Inicio',
                      'link'  => '/',
                      'icon'  => 'fas fa-home'
                    ),
                    array(
                      'title'     => 'Carga de Combustible',
                      'link'      => 'combustibleesp/'.$id.'/show',
                      'icon'      => 'fas fa-truck'
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

    public function prueba()
    {
        $this->layout                   = View::make('prueba');
        $this->layout->title            = 'Carga de Combustible';
        $this->layout->breadcrumb       = array(
            array(
                        'title' => 'Inicio',
                        'link'  => '/',
                        'icon'  => 'fas fa-home'
                      ),
        );
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $asignacion = AsignacionEspecial::destroy($id);
        return Redirect::route('listCombustibleEsp')->with('info', 'Se ha Eliminado con éxito la Carga de Combustible');
    }

    public function destroyComb($id)
    {
        $asignacioncomb = AsignacionCombustible::destroy($id);
        return Redirect::back()->with('info', 'Se ha Eliminado con éxito la Carga de Combustible');
    }
}
