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
Route::post('migrate/clicked', 'UsersController@migrateClicked');
Route::post('migrate/token', 'UsersController@migrateToken');
Route::post('migrate/socials', 'UsersController@migrateSocials');
Route::post('migrate/storetexter', 'UsersController@migrateStoreTexter');*/

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

	Route::get('texts', 'UsersController@getDefaultTexts');
	Route::put('texts/{text}', 'UsersController@updateDefaultTexts');
	Route::get('settings/companyNames', 'UsersController@companyNames');

	Route::post('users/lookup', 'UsersController@lookup');
	Route::get('users/lookup/users', 'UsersController@getNewUsers');
	Route::put('users/company/{user?}', 'UsersController@company');
	Route::get('users/status/{user?}', 'UsersController@status');
	Route::get('users/partners', 'UsersController@partners');
	Route::put('users/partners', 'UsersController@partnersCreate');
	Route::post('users/partners/{user}', 'UsersController@partnersUpdate');
	Route::delete('users/partners/{user}', 'UsersController@partnersRemove');

	Route::get('users/employees', 'UsersController@employees');
	Route::put('users/employees', 'UsersController@employeesCreate');
	Route::post('users/employees/{user}', 'UsersController@employeesUpdate');
	Route::delete('users/employees/{user}', 'UsersController@employeesRemove');

	Route::post('users/password', 'UsersController@password');
	Route::post('users/profile', 'UsersController@profile');
	Route::post('users/saveSettings', 'UsersController@saveSettings');
	Route::get('users/live', 'UsersController@getLiveUsers');
	Route::get('users/free', 'UsersController@getFreeUsers');
	Route::get('users/canceled', 'UsersController@getCanceledUsers');
	Route::put('users', 'UsersController@create');
	Route::post('users/{id}', 'UsersController@update');
	Route::delete('users/{id}', 'UsersController@remove');
	Route::get('users/{id}/magic', 'UsersController@magic');

	Route::get('plans', 'PlansController@all');
	Route::put('plans/save', 'PlansController@savePlan');
	Route::post('plans/subscribe', 'PlansController@subscribe');
	Route::put('plans/subscribe', 'PlansController@updateCard');
	Route::get('plans/get', 'PlansController@getPlanInfo');
	Route::post('plans/free/{user?}', 'PlansController@makeFreePlan');
	Route::post('plans/unsubscribe/{user?}', 'PlansController@cancelSubscription');
	Route::post('plans/reactivate/{user?}', 'PlansController@reactivatePlan');
	Route::post('plans/{plan_id}', 'PlansController@updatePlan');
	Route::delete('plans/{plan_id}', 'PlansController@remove');

	Route::get('links', 'LinksController@all');

	Route::get('teams', 'TeamsController@all');

	Route::any('homeadvisor/answer', 'HomeadvisorController@answer');
	Route::post('homeadvisor/event', 'HomeadvisorController@event');

	Route::post('homeadvisor/calendar', 'HomeadvisorController@googleCalendar');
	Route::post('homeadvisor/token', 'HomeadvisorController@getCalendarToken');

	Route::get('homeadvisor', 'HomeadvisorController@info');
	Route::put('homeadvisor/activate', 'HomeadvisorController@activate');
	Route::post('homeadvisor/activate/{homeadvisor}', 'HomeadvisorController@activateUpdate');
	Route::put('homeadvisor/enable/{homeadvisor}', 'HomeadvisorController@enable');
	Route::put('homeadvisor/', 'HomeadvisorController@create');
	Route::post('homeadvisor/fake', 'HomeadvisorController@sendFake');
	Route::post('homeadvisor/referral', 'HomeadvisorController@sendReferral');
	Route::get('homeadvisor/general', 'HomeadvisorController@getGeneralMessages');
	Route::get('homeadvisor/general/{id}', 'HomeadvisorController@getGeneralMessageWithUser');
	Route::post('homeadvisor/{homeadvisor}', 'HomeadvisorController@update');
	Route::get('pictures', 'HomeadvisorController@pictures');
	Route::post('pictures/remove', 'HomeadvisorController@picturesRemove');
	Route::put('general/send/{message}', 'HomeadvisorController@sendGeneralMessage');

	Route::post('clients/addToList/{id}', 'ClientsController@addToList');
	Route::get('clients/reports/{client}', 'ClientsController@reportsReviews');
	Route::get('clients/leads', 'ClientsController@leads');
	Route::get('clients/vonage', 'ClientsController@vonageLeads');
	Route::get('clients/{id}', 'ClientsController@info');
	Route::get('clients', 'ClientsController@all');
	Route::put('clients', 'ClientsController@create');
	Route::put('clients/disableFollowup/{id}', 'ClientsController@disableFollowup');
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

	Route::get('analysis', 'AnalysisController@all');
	Route::post('analysis/calendar', 'AnalysisController@calendar');

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

	Route::put('appointment/{user}/{client}', 'AppointmentController@create');

	Route::post('upload/file', 'UploadController@save');
	Route::post('upload/fileS3', 'UploadController@saveS3');
	Route::post('upload/csv', 'UploadController@csv');

	Route::get('phones', 'ReportsController@phones');
	Route::post('reports', 'ReportsController@get');
	
	Route::post('leads', 'LeadsController@get');
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

Route::get('ha-job/{user}/{client?}', 'HomeadvisorController@page');

Route::get('general/{message}/bit.ly/{bitly}', 'UsersController@magicGeneral');

Route::get('magic/{dialog}/bit.ly/{bitly}', 'HomeadvisorController@magic');
Route::get('magic/inbox/{user}/{client}/{dialog}', 'UsersController@magicInbox');
Route::get('magic/referral/{hash}', 'UsersController@magicReferral');

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
Route::any('general/push/{message}', 'HomeadvisorController@push');
Route::any('appointment/push/{appointment}', 'AppointmentController@push');

Route::any('inbox/dialog/{dialog}', 'DialogsController@inbox');
Route::any('inbox/message/{message}', 'MessagesController@inbox');
Route::any('inbox/general/{message}', 'HomeadvisorController@inbox');

Route::any('leads/convert', 'HomeadvisorController@convert');

Route::post('stripe/webhook', '\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook');

Route::any('{catchall}', function() {
	return auth()->check() ? view('template') : view('signin');
})->where('catchall', '(.*)');