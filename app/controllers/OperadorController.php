<?php

class OperadorController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->layout        = View::make('sistema.operador.index');
		$this->layout->title = 'Operadores';


    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Operadores',
				'link'  => 'operador',
				'icon'  => 'fas fa-id-card'
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
		$this->layout        = View::make('sistema.operador.create');
		$this->layout->title = 'Operadores';

    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Operadores',
				'link'  => 'operador',
				'icon'  => 'fas fa-id-card'
      ),
      array(
				'title' => 'Nuevo Operador',
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

		// create a new model instance
		$operador = new Operador();

		// attempt validation
		if (!$operador->validate($new))
		{
	    $errors = $operador->errors();
	    return Redirect::to('operador/crear')->withErrors($errors)->withInput();
		}

		try
		{
	    // Create the user
	    $user = Sentry::createUser(array(
					'email'      => Input::get('email'),
					'password'   => 'secreto',
					'activated'  => true,
					'username'   => Input::get('username'),
					'first_name' => Input::get('first_name'),
					'last_name'  => Input::get('last_name'),
	    ));

	    // Find the group using the group id
	    $operadorGroup = Sentry::findGroupByName('Operador');

	    // Assign the group to the user
	    $user->addGroup($operadorGroup);
		}
		catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
		{
		  //echo 'Login field is required.';
		  return Redirect::to('operador/crear')->with('error','Login.')->withInput();
		}
		catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
		{
		  //echo 'Password field is required.';
		  return Redirect::to('operador/crear')->with('error','Password.')->withInput();
		}
		catch (Cartalyst\Sentry\Users\UserExistsException $e)
		{
		  //echo 'User with this login already exists.';
		  return Redirect::to('operador/crear')->with('error','Ya Existe el usuario: '.Input::get('username'))->withInput();
		}
		catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e)
		{
		  //echo 'Group was not found.';
		  return Redirect::to('operador/crear')->with('error','Grupo.')->withInput();
		}
		catch (Exception $e)
		{
			if($e->errorInfo[0] === '23000'){
				return Redirect::to('operador/crear')->with('error','Ya se esta usando el correo: '.Input::get('email'))->withInput();
			} else{
				return Redirect::to('operador/crear')->with('error','Ha ocurrido un error, Intente de nuevo.')->withInput();
			}
		}

		$operador->user_id       = $user->id;
		$operador->nss           = Input::get('nss');
		$operador->telefono      = Input::get('telefono');
		$operador->contacto      = Input::get('contacto');
		$operador->tel_contacto  = Input::get('tel_contacto');
		$operador->vigencia      = Input::get('vigencia');
		$operador->medica        = Input::get('medica');
		$operador->unidad_id     = Input::get('unidad_id');
		$operador->observaciones = Input::get('observaciones');

		if ($operador->save()) {
			return Redirect::to('operador')->with('info','El operador se ha guardado con exito <br> Usuario: '.$user->username.' <br>Contraseña: secreto <br>Por favor pida al Operador que cambie su contraseña inmediatamente.');
		}

		return Redirect::to('operador/crear')->with('error','Ha ocurrido un error. Intente nuevamente.')->withInput();
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
		$this->layout        = View::make('sistema.operador.create');
		$this->layout->title = 'Cliente';
		$this->layout->operador = Operador::find($id);

    // add breadcrumb to current page
    $this->layout->breadcrumb = array(
      array(
				'title' => 'Inicio',
				'link'  => '/',
				'icon'  => 'fas fa-home'
      ),
      array(
				'title' => 'Operadores',
				'link'  => 'operador',
				'icon'  => 'fas fa-id-card'
      ),
      array(
				'title' => 'Editar Operador',
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

		// create a new model instance
		$operador = Operador::find($id);

		// attempt validation

		try
		{
	    // Find the user using the user id
	    $user = Sentry::findUserById($operador->user->id);
	    //return dump($user);
	    // Update the user details
	    $user->first_name = Input::get('first_name');
	    $user->last_name = Input::get('last_name');

	    if ($user->save()) {
				$edit = Input::all();

				if (!$operador->validate($edit,$id))
				{
			    $errors = $operador->errors();
			    return Redirect::to('operador/'.$id.'/editar')->withErrors($errors)->withInput();
				}

				$operador->nss           = Input::get('nss');
				$operador->telefono      = Input::get('telefono');
				$operador->contacto      = Input::get('contacto');
				$operador->tel_contacto  = Input::get('tel_contacto');
				$operador->vigencia      = Input::get('vigencia');
				$operador->medica        = Input::get('medica');
				$operador->unidad_id     = Input::get('unidad_id');
				$operador->observaciones = Input::get('observaciones');

				if ($operador->save()) {
					return Redirect::to('operador')->with('success','El operador se ha guardado con exito');
				}

				return Redirect::to('operador/'.$id.'/editar')->with('error','Ha ocurrido un error. Intente nuevamente.')->withInput();
	    }
		}
		catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
		{
		  //echo 'Login field is required.';
		  return Redirect::to('operador/'.$id.'/editar')->with('error','Login.')->withInput();
		}
		catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
		{
		  //echo 'Password field is required.';
		  return Redirect::to('operador/'.$id.'/editar')->with('error','Password.')->withInput();
		}
		catch (Cartalyst\Sentry\Users\UserExistsException $e)
		{
		  //echo 'User with this login already exists.';
		  return Redirect::to('operador/'.$id.'/editar')->with('error','Ya Existe el usuario: '.Input::get('username'))->withInput();
		}
		catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e)
		{
		  //echo 'Group was not found.';
		  return Redirect::to('operador/'.$id.'/editar')->with('error','Grupo.')->withInput();
		}
		catch (Exception $e)
		{
			if($e->errorInfo[0] === '23000'){
				return Redirect::to('operador/'.$id.'/editar')->with('error','Ya se esta usando el correo: '.Input::get('email'))->withInput();
			} else{
				return Redirect::to('operador/'.$id.'/editar')->with('error','Ha ocurrido un error, Intente de nuevo.')->withInput();
			}
		}


	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$operador = Operador::find($id);
		$user = User::destroy($operador->user_id);
		$operador = Operador::destroy($id);
		return Redirect::to('operador')->with('info','Se ha eliminado al Operador');
	}


}
