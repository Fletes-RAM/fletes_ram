<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		return View::make('hello');
	}

	public function mail()
	{
		$data = Input::all();
		Mail::send('emails.welcome', $data, function($message)
		{
	    $message->to('alejandro@hostlat.com', 'John Smith')->subject('Welcome!');

		});

		return Redirect::refresh()->with('success','Hemos recibido tu mail. Pronto nos pondremos en contacto.');
		
	}

}
