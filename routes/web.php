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

/*Route::post('migrate', 'UsersController@migrate');
Route::post('migrate/phones', 'UsersController@migratePhones');
Route::post('migrate/dialogs', 'UsersController@migrateDialogs');
Route::post('migrate/clicked', 'UsersController@migrateClicked');*/
Route::post('migrate/token', 'UsersController@migrateToken');

Route::get('/view/{folder?}/{file?}/{param?}', function($folder = '', $file = '', $param = '') {
	$view = $folder.(empty($file) ? '' : '.'.$file);
	if (empty($view)) {
		$controller = app()->make('\App\Http\Controllers\PagesController');
		$view = $controller->callAction('defaultPage', []); 
	}
	return view($view);
})->middleware('auth.views');

Route::group(['prefix' => 'api/v1', 'middleware' => ['messages', 'timezone']], function() {
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

	Route::get('links', 'LinksController@all');

	Route::get('teams', 'TeamsController@all');

	Route::get('homeadvisor', 'HomeadvisorController@info');
	Route::put('homeadvisor/activate', 'HomeadvisorController@activate');
	Route::post('homeadvisor/activate/{homeadvisor}', 'HomeadvisorController@activateUpdate');
	Route::put('homeadvisor/enable/{homeadvisor}', 'HomeadvisorController@enable');
	Route::put('homeadvisor/', 'HomeadvisorController@create');
	Route::post('homeadvisor/fake', 'HomeadvisorController@sendFake');
	Route::post('homeadvisor/{homeadvisor}', 'HomeadvisorController@update');

	Route::post('clients/addToList/{id}', 'ClientsController@addToList');
	Route::get('clients/reports/{client}', 'ClientsController@reportsReviews');
	Route::get('clients/leads', 'ClientsController@leads');
	Route::get('clients/{id}', 'ClientsController@info');
	Route::get('clients', 'ClientsController@all');
	Route::put('clients', 'ClientsController@create');
	Route::post('clients/{id}', 'ClientsController@update');
	Route::delete('clients/{id}', 'ClientsController@remove');

	Route::put('clients/createForList/{list}', 'ClientsController@createForList');
	Route::post('clients/updateForList/{list}/{client}', 'ClientsController@updateForList');
	Route::delete('clients/removeFromList/{list}/{client}', 'ClientsController@removeFromList');

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
	Route::post('messages/textValidate', 'MessagesController@textValidate');
	Route::post('messages/{id}', 'MessagesController@update');
	Route::delete('messages/{id}', 'MessagesController@remove');
	Route::get('messages', 'MessagesController@all');
	Route::get('messages/{id}', 'MessagesController@info');
	Route::post('messages/changeActive/{message}', 'MessagesController@changeActive');

	Route::get('lists', 'ListsController@all');
	Route::put('lists/save', 'ListsController@save');
	Route::post('lists/{id}', 'ListsController@save');
	Route::delete('lists/{id}', 'ListsController@remove');

	Route::get('dialogs', 'DialogsController@all');
	Route::get('dialogs/{id}', 'DialogsController@info');
	Route::put('dialogs/create/{client}', 'DialogsController@create');

	Route::post('upload/file', 'UploadController@save');
	Route::post('upload/csv', 'UploadController@csv');
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

Route::get('magic/{dialog}/bit.ly/{bitly}', 'HomeadvisorController@magic');

Route::any('de83020eb8e0b2b1840734bb34a00f0f/get_fb_token', 'UsersController@facebookToken');
Route::any('save_fb_reviews', 'UsersController@facebookReviews');

Route::any('de83020eb8e0b2b1840734bb34a00f0f/get_google_place', 'UsersController@googlePlaceId');
Route::any('save_google_reviews', 'UsersController@googleReviews');

Route::get('seances/{code}', 'AnswersController@text');
Route::get('seances/{id}/{value}', 'AnswersController@email');

Route::any('home-advisor/{code?}', 'HomeadvisorController@lead');

Route::any('company/push', 'UsersController@push');
Route::any('review/push/{review}', 'SeancesController@push');
Route::any('message/push/{text}', 'MessagesController@push');
Route::any('dialog/push/{dialog}', 'DialogsController@push');

Route::any('inbox/dialog/{dialog}', 'DialogsController@inbox');
Route::any('inbox/message/{message}', 'MessagesController@inbox');

Route::any('{catchall}', function() {
	return auth()->check() ? view('template') : view('signin');
})->where('catchall', '(.*)');