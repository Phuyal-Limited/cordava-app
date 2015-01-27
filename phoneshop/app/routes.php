<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


App::missing(function($exception)
{
    return array(
    			'message' => 'Page Not Found.',
    			'errors' => '',
    			'statusCode' => 404,
    			);
});

Route::get('login/checkvalid', 'LoginController@getCheckvalid');

Route::group(array('before'=>'guest'), function(){
	Route::post('login', 'LoginController@getIndex');
	Route::controller('login', 'LoginController');
});

//Put the login protected routes below
Route::group(array('before'=>'auth'), function(){
 	Route::get('logout', 'LogoutController@getIndex');
 	Route::controller('logout', 'LogoutController');
 	Route::controller('customer', 'CustomerController');
 	Route::controller('transaction', 'TransactionController');
 	Route::controller('article', 'ArticleController');
});

