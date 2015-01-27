<?php

class LoginController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:	|
	| Route::get('/', 'HomeController@showWelcome');
	|
	*/

	// public function __construct() {
	// $this->beforeFilter('csrf', array('on'=>'post'));
	// }

	public function postIndex(){
		$validator = Validator::make(Input::all(),array(
			'email' => 'required|email',
			'password' => 'required'
			));
		if($validator->fails()){
			return array(
						'message' => 'Data Error.',
						'errors' => $validator->messages(),
						'statusCode' => 400,
						);
		}else{
			$user = array(
						'email' => Input::get('email'),
						'password' => Input::get('password'),
						);
			$remember = Input::get('remember');
			$userdata = User::where('email', Input::get('email'))->first();
			
			if(empty($userdata)){
				return array(
							'message' => 'The email or password provided is incorrect.',
							'errors' => '',
							'statusCode' => 401,
							);
			}

			if(Auth::attempt($user,$remember)){
				Session::put('user_id', Auth::user()->id);
				return array(
							'message' => 'Success.',
							'errors' => '',
							'statusCode' => 200,
							);
			}else{
				return array(
							'message' => 'The email or password provided is incorrect.',
							'errors' => '',
							'statusCode' => 401,
							);
			}
			return array(
						'message' => 'There was a problem signing you in.',
						'errors' => '',
						'statusCode' => 402,
						);
		}
	}

	public function getCheckvalid(){
		if(Auth::check()){
			return array(
						'message' => 'You are currently logged in.',
						'errors' => '',
						'statusCode' => 201,
						);
		}else{
			return array(
						'message' => 'You are not logged in to the system.',
						'errors' => '',
						'statusCode' => 202,
						);
		}
	}
}