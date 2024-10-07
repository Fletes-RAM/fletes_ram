<?php

class RecoverController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('dashboard.recover');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

		try
		{
		    // Find the user using the user email address
				$user       = Sentry::findUserByLogin(Input::get('email'));
				
				// Get the password reset code
				$resetCode  = $user->getResetPasswordCode();
				
				$user_id    = $user->id;
				
				$action_url = action('recover.newPassword',[$user_id,$resetCode]);
				$name       = $user->first_name.' '.$user->last_name;
				$email      = $user->email;

				$data = array(
					'action_url' => $action_url,
					'name'       => $name,
					'email'      => $email
				);

		    Mail::send('emails.rec-pass', $data, function($message) use ($action_url, $name, $email)
		    {
		        $message->to($email, $name)->subject('Cambio de Contraseña');
		    });

		    return Redirect::to('sistema_ram');
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    echo 'User was not found.';
		}

	}

	public function newPassword($id,$resetCode)
	{
		
		return View::make('dashboard.new-password', compact(['id','resetCode']));

	}

	public function newPasswordPost($id,$resetCode)
	{

		$rules = array(
			'password'              => 'required|min:8|alpha_dash|confirmed',
			'password_confirmation' => 'required|min:8'
			);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$errors = $validator->errors();
			return Redirect::route('recover.newPassword',[$id,$resetCode])->withErrors($errors);
		}

		try
		{
		    // Find the user using the user id
			$user = Sentry::findUserById($id);

		    // Check if the reset password code is valid
			if ($user->checkResetPasswordCode($resetCode))
			{
		        // Attempt to reset the user password
				if ($user->attemptResetPassword($resetCode, Input::get('password')))
				{
		            return Redirect::to('sistema_ram')->with('success','Se ha cambiado con éxito la contraseña');
				}
				else
				{
		            return Redirect::to('sistema_ram')->with('error','No se ha cambiado la contraseña');
				}
			}
			else
			{
		        return Redirect::to('/')->with('error','Ha ocurrido un error. Intente nuevamente.');
			}
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			echo 'User was not found.';
		}

	}

}
