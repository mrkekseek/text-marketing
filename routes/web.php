<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/view/{folder?}/{file?}/{param?}', function($folder = '', $file = '', $param = '') {
	$view = $folder.(empty($file) ? '' : '.'.$file);
	if (empty($view)) {
		$controller = app()->make('\App\Http\Controllers\PagesController');
		$view = $controller->callAction('defaultPage', []); 
	}
	return view($view);
})->middleware('auth.views');

Route::group(['prefix' => 'api/v1', 'middleware' => 'messages'], function() {
	//Route::any('{sunit}/{sid?}/{smethod?}', 'ApiController@run')->middleware('messages');

	Route::post('auth/signin', 'AuthController@signin');
	Route::get('auth/signout', 'AuthController@signout');
	Route::get('auth/info', 'AuthController@info');

	Route::get('pages/menu', 'PagesController@menu');

	Route::post('users/password', 'UsersController@password');
	Route::get('users', 'UsersController@all');
	Route::put('users', 'UsersController@create');
	Route::post('users/{id}', 'UsersController@update');
	Route::delete('users/{id}', 'UsersController@remove');
	Route::get('users/{id}/magic', 'UsersController@magic');

	Route::get('plans', 'PlansController@all');
});

Route::get('signup/{type?}', function($type = false) {
	return view('signup')->with(['type' => $type]);
});

Route::get('support', function() {
	return view('support');
});

Route::get('recovery', function() {
	return view('recovery');
});

Route::get('survey/{param?}', 'SeancesController@getSeance');
Route::get('email/answers/{id?}/{value?}', 'AnswersController@saveEmail');

Route::any('home-advisor/{code?}', 'HomeadvisorController@saveLead');

Route::any('{catchall}', function() {
	return auth()->check() ? view('template') : view('signin');
})->where('catchall', '(.*)');