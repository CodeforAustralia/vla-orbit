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

Route::get('/matter/new', 'MatterController@create');

Route::get('/matter/show/{m_id}', 'MatterController@show');

//Matter Type
Route::get('/matter_type', 'MatterTypeController@index');

Route::get('/matter_type/show/{mt_id}', 'MatterTypeController@show');

Route::get('/matter_type/new', 'MatterTypeController@create');

Route::post('/matter_type', 'MatterTypeController@store');

//Service
Route::get('/service', 'ServiceController@index');

Route::get('/service/show/{sv_id}', 'ServiceController@show');

Route::get('/service/new', 'ServiceController@create');

//Service Provider
Route::get('/service_providers', 'ServiceProvidersController@index');

Route::get('/service_providers/show/{sv_id}', 'ServiceProvidersController@show');

Route::get('/service_providers/new', 'ServiceProvidersController@create');

//Booking
Route::get('/booking', 'BookingController@index');

Route::get('/booking/show/{bk_id}', 'BookingController@show');

Route::get('/booking/new', 'BookingController@create');

//Referral
Route::get('/referral', 'ReferralController@index');

Route::get('/referral/show/{rf_id}', 'ReferralController@show');

Route::get('/referral/new', 'ReferralController@create');