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

	Route::post('auth/support', 'AuthController@support');
	Route::post('auth/signup', 'AuthController@signup');
	Route::post('auth/signin', 'AuthController@signin');
	Route::post('auth/recovery', 'AuthController@recovery');
	Route::get('auth/signout', 'AuthController@signout');
	Route::get('auth/info', 'AuthController@info');

	Route::get('pages/menu', 'PagesController@menu');

	Route::put('users/company/{user?}', 'UsersController@company');
	Route::get('users/status/{user?}', 'UsersController@status');
	Route::get('users/partners', 'UsersController@partners');
	Route::put('users/partners', 'UsersController@partnersCreate');
	Route::post('users/partners/{user}', 'UsersController@partnersUpdate');
	Route::delete('users/partners/{user}', 'UsersController@partnersRemove');
	Route::post('users/password', 'UsersController@password');
	Route::post('users/profile', 'UsersController@profile');
	Route::post('users/saveSettings', 'UsersController@saveSettings');
	Route::get('users', 'UsersController@all');
	Route::put('users', 'UsersController@create');
	Route::post('users/{id}', 'UsersController@update');
	Route::delete('users/{id}', 'UsersController@remove');
	Route::get('users/{id}/magic', 'UsersController@magic');

	Route::get('plans', 'PlansController@all');

	Route::put('homeadvisor/activate', 'HomeadvisorController@activate');
	Route::get('homeadvisor/info', 'HomeadvisorController@info');
	Route::post('homeadvisor/{id}', 'HomeadvisorController@save');

	Route::post('clients/addToList/{id}', 'ClientsController@addToList');
	Route::get('clients/leads', 'ClientsController@leads');
	Route::get('clients/{id}', 'ClientsController@info');
	Route::get('clients', 'ClientsController@all');
	Route::put('clients', 'ClientsController@create');
	Route::post('clients/{id}', 'ClientsController@update');
	Route::delete('clients/{id}', 'ClientsController@remove');

	Route::put('surveys', 'SurveysController@save');
	Route::post('surveys/text/{user?}', 'SurveysController@text');
	Route::post('surveys/email/{user?}', 'SurveysController@email');
	Route::get('surveys/{user?}', 'SurveysController@info');

	Route::get('urls', 'UrlsController@all');
	Route::put('urls', 'UrlsController@create');
	Route::post('urls/{url}', 'UrlsController@update');
	Route::post('urls/bulk/{user}', 'UrlsController@bulkUpdate');
	Route::delete('urls/{url}', 'UrlsController@remove');

	Route::put('seances/{user?}', 'SeancesController@create');
	Route::put('seances/{seance}/tap', 'SeancesController@tap');

	Route::put('answers/{id}', 'AnswersController@save');

	Route::put('messages/create', 'MessagesController@create');
	Route::delete('messages/{id}', 'MessagesController@remove');
	Route::get('messages', 'MessagesController@all');
	Route::get('messages/{id}', 'MessagesController@info');
	Route::post('messages/textValidate', 'MessagesController@textValidate');

	Route::get('lists', 'ListsController@all');
	Route::put('lists/save', 'ListsController@save');
	Route::post('lists/{id}', 'ListsController@save');
	Route::delete('lists/{id}', 'ListsController@remove');
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

Route::get('seances/{code}', 'AnswersController@text');
Route::get('seances/{id}/{value}', 'AnswersController@email');

Route::any('home-advisor/{code?}', 'HomeadvisorController@saveLead');

Route::any('company/push', 'UsersController@push');
Route::any('review/push/{review}', 'SeancesController@push');

Route::any('{catchall}', function() {
	return auth()->check() ? view('template') : view('signin');
})->where('catchall', '(.*)');