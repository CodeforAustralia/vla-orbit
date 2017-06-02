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

Route::get('/', 'RegistrationController@index')->name('home');

Route::get('/register', 'RegistrationController@create');
Route::post('/register', 'RegistrationController@store');

Route::get('/login', 'SessionController@create')->name('login');
Route::post('/login', 'SessionController@store');
Route::get('/logout', 'SessionController@destroy');

Route::get('/outlook', 'SessionController@outlook');


Route::get('/soap/types', 'MasterController@_types');
Route::get('/soap/functions', 'MasterController@_functions');

//Auth from Outlook

Route::get('/signin', 'AuthController@signin');

Route::get('/authorize', 'AuthController@gettoken');

Route::get('/mail', 'OutlookController@mail')->name('mail');

//Comments
Route::post('/posts/{post}/comments','CommentsController@store') ;

//posts
Route::post('/posts', 'PostsController@store');

Route::get('/posts/', 'PostsController@index');
Route::get('/posts/create', 'PostsController@create');
Route::get('/posts/{post}', 'PostsController@show');

//Matter
Route::get('/matter', 'MatterController@index');

Route::get('/matter/list', 'MatterController@list');

Route::get('/matter/listFormated', 'MatterController@listFormated');

Route::get('/matter/new', 'MatterController@create');

Route::get('/matter/show/{m_id}', 'MatterController@show');

Route::get('/matter/delete/{m_id}', 'MatterController@destroy');

Route::post('/matter', 'MatterController@store');

//Matter Type
Route::get('/matter_type', 'MatterTypeController@index');

Route::get('/matter_type/list', 'MatterTypeController@list');

Route::get('/matter_type/show/{mt_id}', 'MatterTypeController@show');

Route::get('/matter_type/delete/{mt_id}', 'MatterTypeController@destroy');

Route::get('/matter_type/new', 'MatterTypeController@create');

Route::post('/matter_type', 'MatterTypeController@store');

//Service
Route::get('/service', 'ServiceController@index');

Route::get('/service/list', 'ServiceController@list');

Route::get('/service/show/{sv_id}', 'ServiceController@show');

Route::get('/service/delete/{sv_id}', 'ServiceController@destroy');

Route::get('/service/new', 'ServiceController@create');

Route::post('/service', 'ServiceController@store');

//Service Type
Route::get('/service_type', 'ServiceTypeController@index');

Route::get('/service_type/list', 'ServiceTypeController@list');

Route::get('/service_type/show/{st_id}', 'ServiceTypeController@show');

Route::get('/service_type/delete/{st_id}', 'ServiceTypeController@destroy');

Route::get('/service_type/new', 'ServiceTypeController@create');

Route::post('/service_type', 'ServiceTypeController@store');

//Service Level
Route::get('/service_level', 'ServiceLevelController@index');

Route::get('/service_level/list', 'ServiceLevelController@list');

Route::get('/service_level/show/{sl_id}', 'ServiceLevelController@show');

Route::get('/service_level/delete/{sl_id}', 'ServiceLevelController@destroy');

Route::get('/service_level/new', 'ServiceLevelController@create');

Route::post('/service_level', 'ServiceLevelController@store');

//Service Provider
Route::get('/service_provider', 'ServiceProvidersController@index');

Route::get('/service_provider/list', 'ServiceProvidersController@list');

Route::get('/service_provider/show/{sp_id}', 'ServiceProvidersController@show');

Route::get('/service_provider/delete/{sp_id}', 'ServiceProvidersController@destroy');

Route::get('/service_provider/new', 'ServiceProvidersController@create');

Route::post('/service_provider', 'ServiceProvidersController@store');

//Catchment
Route::get('/catchment', 'CatchmentController@index');

Route::get('/catchment/list', 'CatchmentController@list');

Route::get('/catchment/listFormated', 'CatchmentController@listFormated');

Route::get('/catchment/new', 'CatchmentController@create');

Route::get('/catchment/show/{ca_id}', 'CatchmentController@show');

Route::get('/catchment/delete/{ca_id}', 'CatchmentController@destroy');

Route::post('/catchment', 'CatchmentController@store');


//Booking
Route::get('/booking', 'BookingController@index');

Route::get('/booking/show/{bk_id}', 'BookingController@show');

Route::get('/booking/new', 'BookingController@create');

//Referral
Route::get('/referral', 'ReferralController@index');

Route::get('/referral/show/{rf_id}', 'ReferralController@show');

Route::get('/referral/new', 'ReferralController@create');