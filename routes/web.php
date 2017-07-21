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

Route::get('/service/list_services_sp/{sp_id}', 'ServiceController@listServicesSP');

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

Route::get('/catchment/listLgcs', 'CatchmentController@ListLgcs');

Route::get('/catchment/listSuburbs', 'CatchmentController@ListSuburbs');

Route::get('/catchment/new', 'CatchmentController@create');

Route::get('/catchment/show/{ca_id}', 'CatchmentController@show');

Route::get('/catchment/delete/{ca_id}', 'CatchmentController@destroy');

Route::post('/catchment', 'CatchmentController@store');

//Question Category
Route::get('/question_category', 'QuestionCategoryController@index');

Route::get('/question_category/list', 'QuestionCategoryController@list');

Route::get('/question_category/show/{qc_id}', 'QuestionCategoryController@show');

Route::get('/question_category/delete/{qc_id}', 'QuestionCategoryController@destroy');

Route::get('/question_category/new', 'QuestionCategoryController@create');

Route::post('/question_category', 'QuestionCategoryController@store');


//Question Type
Route::get('/question_type', 'QuestionTypeController@index');

Route::get('/question_type/list', 'QuestionTypeController@list');

Route::get('/question_type/show/{qc_id}', 'QuestionTypeController@show');

Route::get('/question_type/delete/{qc_id}', 'QuestionTypeController@destroy');

Route::get('/question_type/new', 'QuestionTypeController@create');

Route::post('/question_type', 'QuestionTypeController@store');

//Question
Route::get('/legal_matter_questions', 'QuestionController@legalMatterQuestions');

Route::get('/eligibility_criteria', 'QuestionController@eligibilityCriteria');

Route::get('/question', 'QuestionController@index');

Route::get('/question/list', 'QuestionController@list');

Route::get('/question/list_legal_matter', 'QuestionController@listLegalMatterQuestions');

Route::get('/question/list_eligibility', 'QuestionController@listVulnerabilityQuestions');

Route::get('/question/show/{qu_id}', 'QuestionController@show');

Route::get('/question/delete/{qu_id}', 'QuestionController@destroy');

Route::get('/question/new/{type?}', 'QuestionController@create');

Route::post('/question', 'QuestionController@store');

//Booking
Route::get('/booking', 'BookingController@index');

Route::get('/booking/show/{bk_id}', 'BookingController@show');

Route::get('/booking/new', 'BookingController@create');

Route::get('/booking/delete/{bo_id}', 'BookingController@destroy');

Route::get('/booking/listDatesByMonth/{month}/{sv_id}', 'BookingController@getServiceDatesByMonth');

Route::get('/booking/list', 'BookingController@list');

Route::get('/booking/listCalendar', 'BookingController@listCalendar');

Route::post('/booking', 'BookingController@store');

//Referral
Route::get('/referral', 'ReferralController@index');

Route::get('/referral/show/{rf_id}', 'ReferralController@show');

Route::get('/referral/new', 'ReferralController@create');

Route::get('/referral/create/location', 'ReferralController@location');

Route::get('/referral/create/legal_issue', 'ReferralController@legal_issue');

Route::get('/referral/create/details', 'ReferralController@details');

Route::get('/referral/create/questions', 'ReferralController@questions');

Route::get('/referral/create/review', 'ReferralController@review');

Route::get('/referral/list', 'ReferralController@list');

//Route::get('/referral/result', 'ReferralController@result');

Route::get('/referral/create/result', 'ReferralController@result');

Route::post('/referral/create/result', 'ReferralController@result');

Route::post('/referral', 'ReferralController@store');

//Users

Route::get('/user', 'UserController@index');

Route::get('/user/show/{us_id}', 'UserController@show');

Route::get('/user/new', 'UserController@create');

Route::get('/user/list', 'UserController@list');

Route::post('/user', 'UserController@store');