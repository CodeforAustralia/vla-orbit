 <?php
/**
 * Web routes for Orbit application
 *
 * @author Christian Arevalo and Sebastian Currea
 * @version 1.2.0
 */


Route::get('/soap/types', 'MasterController@_types');

Route::get('/soap/functions', 'MasterController@_functions');

//Route::get('/mail', 'OutlookController@mail')->name('mail');

//Comments
//Route::post('/posts/{post}/comments', 'CommentsController@store') ;

//posts
//Route::post('/posts', 'PostsController@store');

// Route::get('/posts/', 'PostsController@index');
// Route::get('/posts/create', 'PostsController@create');
// Route::get('/posts/{post}', 'PostsController@show');

//Matter
Route::get('/matter', 'MatterController@index');

Route::get('/matter/list', 'MatterController@list');

Route::get('/matter/listFormated', 'MatterController@listFormated');

Route::get('/matter/listWithQuestionsFormated', 'MatterController@listWithQuestionsFormated');

Route::get('/matter/listFormatedTrimmed', 'MatterController@listFormatedTrimmed');

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
Route::get('/service', 'ServiceController@index')->name('serviceList');

Route::get('/service/my_services', 'ServiceController@myServices');

Route::get('/service/list', 'ServiceController@list');

Route::get('/service/list_without_update/{days}', 'ServiceController@listWithoutUpdate');

Route::get('/service/list_table', 'ServiceController@listTable');

Route::get('/service/list_service_by_id/{sv_id}', 'ServiceController@listServiceById');

Route::get('/service/list_services_sp/{sp_id}', 'ServiceController@listServicesSP');

Route::get('/service/list_own_services/{sp_id}', 'ServiceController@listOwnServices');

Route::get('/service/show/{sv_id}', 'ServiceController@show');

Route::get('/service/delete/{sv_id}', 'ServiceController@destroy');

Route::get('/service/new', 'ServiceController@create');

Route::post('/service', 'ServiceController@store');

Route::post('/service/general_settings', 'ServiceController@storeGeneralSettings');

Route::post('/service/intake_options', 'ServiceController@storeIntakeOptions');

Route::post('/service/client_eligibility', 'ServiceController@storeClientEligibility');

Route::post('/service/legal_matter', 'ServiceController@storeLegalMatter');

Route::post('/service/request_addition', 'ServiceController@sendRequestEmail');

Route::post('/service/send_notification', 'ServiceController@sendNotification');

// Outdated Services
Route::get('/outdated_services', 'OutdatedServiceController@index');

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

Route::post('/service_provider/listFormated', 'ServiceProvidersController@listFormated');

Route::post('/service_provider/list_bookable_formated', 'ServiceProvidersController@listBookableFormated');

Route::get('/service_provider/show/{sp_id}', 'ServiceProvidersController@show');

Route::get('/service_provider/delete/{sp_id}', 'ServiceProvidersController@destroy');

Route::get('/service_provider/new', 'ServiceProvidersController@create');

Route::get('/service_provider/getById/{sp_id}', 'ServiceProvidersController@getById');

Route::post('/service_provider', 'ServiceProvidersController@store');

Route::post('/service_provider/set_sp', 'ServiceProvidersController@setServiceProvider');

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

//Question Group
Route::get('/question_group', 'QuestionGroupController@index');

Route::get('/question_group/list', 'QuestionGroupController@list');

Route::get('/question_group/list_formated', 'QuestionGroupController@listFormated');

Route::get('/question_group/show/{qg_id}', 'QuestionGroupController@show');

Route::get('/question_group/delete/{qg_id}', 'QuestionGroupController@destroy');

Route::get('/question_group/new', 'QuestionGroupController@create');

Route::post('/question_group', 'QuestionGroupController@store');

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

Route::get('/service_booking_questions', 'QuestionController@serviceBookingQuestions');

Route::get('/question', 'QuestionController@index');

Route::get('/question/list', 'QuestionController@list');

Route::get('/question/list_legal_matter', 'QuestionController@listLegalMatterQuestions');

Route::get('/question/list_service_booking_questions', 'QuestionController@listServiceBookingQuestions');

Route::get('/question/list_eligibility', 'QuestionController@listVulnerabilityQuestions');

Route::get('/question/show/{qu_id}', 'QuestionController@show');

Route::get('/question/delete/{qu_id}', 'QuestionController@destroy');

Route::get('/question/new/{type?}', 'QuestionController@create');

Route::post('/question', 'QuestionController@store');

//Booking
Route::get('/booking', 'BookingController@index');

Route::get('/booking/next_bookings', 'BookingController@nextBookings');

Route::get('/booking/show/{bk_id}', 'BookingController@show');

Route::get('/booking/new', 'BookingController@create');

Route::get('/booking/delete/{bo_id}', 'BookingController@destroy');

Route::get('/booking/listDatesByDate/{year}/{month}/{sv_id}', 'BookingController@getServiceDatesByDate');

Route::get('/booking/list', 'BookingController@list');

Route::get('/booking/listCalendar', 'BookingController@listCalendar');

Route::get('/booking/listCalendarBySp', 'BookingController@listCalendarBySp');

Route::get('/booking/listCalendarByUser', 'BookingController@listCalendarByUser');

Route::get('/booking/updateBooking/{booking_ref}/{date_time}', 'BookingController@updateBooking');

Route::post('/booking/updateBookingByRef', 'BookingController@updateBooking');

Route::post('/booking/updateBooking', 'BookingController@updateBookingDetails');

Route::post('/booking', 'BookingController@store');

Route::get('/booking/sendSmsReminder', 'BookingController@sendSmsReminder');

Route::get('/booking/listLegalHelpBookings', 'BookingController@listLegalHelpBookings');

Route::get('/booking/legalHelp', 'BookingController@legalHelp');

//Booking Engine

Route::get('/booking/services', 'BookingEngineController@index');

Route::get('/booking/resources', 'BookingEngineController@resources');

Route::get('/booking/services/days/{sv_id}', 'BookingEngineController@getServiceDays');

Route::get('/booking/services/hours/{sv_id}', 'BookingEngineController@getServiceHours');

Route::get('/booking/by_service_provider', 'BookingController@byServiceProvider');

Route::get('/booking/service/{service_id}/booking/{start_date}/{end_date}', 'BookingEngineController@getServiceBookings');

Route::get('/booking/service_provider/booking', 'BookingEngineController@getServiceBookingsBySP');

Route::get('/booking/service/{sv_id}/getAvailability/{start_date}/{end_date}', 'BookingEngineController@getServiceAvailability');

Route::patch('/booking', 'BookingController@updateBooking');

Route::delete('/booking/{booking_id}', 'BookingEngineController@deleteBooking');

Route::get('/booking/upcoming', 'BookingEngineController@nextBookings');

Route::get('/booking/user', 'BookingEngineController@user');
//Route::get('/booking/listServices', 'BookingEngineController@listServices');

//Referral
Route::get('/referral', 'ReferralController@index');

Route::get('/referral/outbound', 'ReferralController@outbound');

Route::get('/referral/show/{rf_id}', 'ReferralController@show');

Route::get('/referral/new', 'ReferralController@create');

Route::get('/referral/create/location', 'ReferralController@location');

Route::get('/referral/create/details', 'ReferralController@details');

Route::get('/referral/create/questions', 'ReferralController@questions');

Route::get('/referral/create/review', 'ReferralController@review');

Route::get('/referral/list', 'ReferralController@list');

Route::get('/referral/listOutbound', 'ReferralController@listOutbound');

Route::get('/referral/list_pager', 'ReferralController@listPager');

Route::get('/referral/list_outbound_pager', 'ReferralController@listOutboundPager');

//Route::get('/referral/result', 'ReferralController@result');

Route::get('/referral/create/result', 'ReferralController@result');

Route::post('/referral/create/result', 'ReferralController@result');

Route::post('/referral', 'ReferralController@store');

//Users

Route::get('/user', 'UserController@index');

Route::get('/user/information', 'UserController@information');

Route::get('/user/show/{uid}', 'UserController@show');

Route::get('/user/delete/{uid}', 'UserController@destroy');

Route::get('/user/new', 'UserController@create');

Route::get('/user/list', 'UserController@list');

Route::get('/user/list_table', 'UserController@listTable');

Route::get('/user/clearNotifications', 'UserController@clearNotifications');

Route::post('/user/update', 'UserController@update');

Route::post('/user', 'UserController@store');

// Change Password

Route::get('/change_password', 'UserController@changePassword');
Route::get('/forgotpassword', 'UserController@forgotPassword');

Route::post('/change_password', 'UserController@changeUserPassword');

//SMS Templates

Route::get('/sms_template', 'SmsTemplateController@index');

Route::get('/sms_template/show/{st_id}', 'SmsTemplateController@show');

Route::get('/sms_template/delete/{st_id}', 'SmsTemplateController@destroy');

Route::get('/sms_template/new', 'SmsTemplateController@create');

Route::get('/sms_template/list', 'SmsTemplateController@list');

Route::post('/sms_template', 'SmsTemplateController@store');

Route::get('/sms_template/getTemplateByServiceBookingId', 'SmsTemplateController@getTemplateByServiceBookingId');

//Statistics

Route::get('/statistic', 'StatisticController@index');

Route::get('/statistic/list', 'StatisticController@listStatistics');

//No Reply emails

Route::get('/no_reply_emails', 'NoReplyEmailController@index');

Route::get('/no_reply_emails/new', 'NoReplyEmailController@create');

Route::get('/no_reply_emails/templates', 'NoReplyEmailController@indexTemplates');

Route::get('/no_reply_emails/templates/new', 'NoReplyEmailController@createTemplate');

Route::get('/no_reply_emails/templates/show/{te_id}', 'NoReplyEmailController@show');

Route::get('/no_reply_emails/templates/delete/{te_id}', 'NoReplyEmailController@destroyTemplate');

Route::get('/no_reply_emails/listAllTemplates', 'NoReplyEmailController@listAllTemplates');

Route::get('/no_reply_emails/listAllLogRecords', 'NoReplyEmailController@listAllLogRecords');

Route::get('/no_reply_emails/listTemplateById', 'NoReplyEmailController@listTemplateById');

Route::get('/no_reply_emails/listAllTemplatesBySection', 'NoReplyEmailController@listAllTemplatesBySection');

Route::get('/no_reply_emails/listAllMailBoxes', 'NoReplyEmailController@listAllMailBoxes');

Route::get('/no_reply_emails/listTemplatesBySectionFormated', 'NoReplyEmailController@listAllTemplatesFormated');

Route::post('/no_reply_emails', 'NoReplyEmailController@sendEmail');

Route::post('/no_reply_emails/templates', 'NoReplyEmailController@saveTemplate');

Route::get('/no_reply_emails/listAllLogRecordBySection', 'NoReplyEmailController@getAllLogRecordBySection');

// Dashboard admin

Route::get('/dashboard', 'DashboardController@index');

Route::get('/dashboard/new', 'DashboardController@create');

Route::get('/dashboard/show/{id}', 'DashboardController@show');

Route::get('/dashboard/delete/{id}', 'DashboardController@destroy');

Route::delete('/dashboard/{id}', 'DashboardController@destroy');

Route::post('/dashboard', 'DashboardController@store');

Route::post('/dashboard/updatePositions', 'DashboardController@updatePositions');

// Service Booking

Route::get('/service_booking', 'ServiceBookingController@index');

Route::get('/service_booking/list', 'ServiceBookingController@list');

Route::get('/service_booking/show/{sb_id}', 'ServiceBookingController@show');

Route::get('/service_booking/new', 'ServiceBookingController@create');

Route::post('/service_booking', 'ServiceBookingController@store');

Route::get('/service_booking/delete/{sb_id}', 'ServiceBookingController@destroy');

Route::post('/service_booking/activate_service', 'ServiceBookingController@activateService');
// Panel Lawyers

Route::get('/panel_lawyers', 'PanelLawyersController@index');

Route::get('/panel_lawyers/list', 'PanelLawyersController@list');

Route::post('/panel_lawyers/get_closet_by_address', 'PanelLawyersController@getClosestByAddress');

Route::post('/panel_lawyers/get_lat_lng', 'PanelLawyersController@getLatLngByAddress');

// E-Referral

Route::get('/e_referral', 'EReferralController@index');

Route::get('/e_referral/list', 'EReferralController@list');

Route::get('/e_referral/new', 'EReferralController@create');

Route::get('/e_referral/show/{erf_id}', 'EReferralController@show');

Route::get('/e_referral/delete/{erf_id}', 'EReferralController@destroy');

Route::get('/e_referral/list', 'EReferralController@list');

Route::get('/e_referral/list_by_id/{erf_id}', 'EReferralController@listById');

Route::get('/e_referral/listFormsFormated', 'EReferralController@listFormsFormated');

Route::post('/e_referral', 'EReferralController@store');

// Configuration

Route::get('/configuration', 'ConfigurationController@index');

Route::get('/configuration/list_table', 'ConfigurationController@listTable');

Route::get('/configuration/new', 'ConfigurationController@create');

Route::get('/configuration/show/{configuration}', 'ConfigurationController@show');

Route::get('/configuration/delete/{configuration}', 'ConfigurationController@destroy');

Route::post('/configuration', 'ConfigurationController@store');

// Service Log

Route::get('/service_log/{sv_id}', 'ServiceLogController@getBySvId');


// Login && Auth routes
//Route::post('/password/reset', 'RegistrationController@create');

Route::get('/', 'RegistrationController@index')->name('home');

Route::get('/registration', 'RegistrationController@registration');

Route::get('/information', 'RegistrationController@information');

Route::post('/information', 'RegistrationController@contact');

Route::post('/contact_information', 'InformationController@contact');

Route::get('/register', 'RegistrationController@create');

Route::post('/register', 'RegistrationController@store');

Route::post('/login', 'SessionController@store');

Route::get('/logout', 'SessionController@destroy');

Route::get('/signup', 'SessionController@sendEmail');

//Auth::routes();

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.email');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');

Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.passwords.reset', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::get('/forgot-password', function () {
    return view('auth.passwords.reset');
})->middleware('guest')->name('password.request');

Route::get('/login_vla', 'SessionController@create')->name('login_vla');

Route::get('/login', 'SessionController@create')->name('login');


//Route::redirect('/', 'SessionController@store');

Route::get('verify/resend', 'Auth\TwoFactorController@resend')->name('verify.resend');
Route::resource('verify', 'Auth\TwoFactorController');

Route::group(['middleware' => ['twofactor']], function () {
    Route::get('/statistic', 'StatisticController@index');
    Route::resource('dashboard', 'DashboardController');
    Route::get('/dashboard/new', 'DashboardController@create');

    // Configuration
    Route::get('/configuration', 'ConfigurationController@index');
    Route::get('/configuration/list_table', 'ConfigurationController@listTable');
    Route::get('/configuration/new', 'ConfigurationController@create');
    Route::get('/configuration/show/{configuration}', 'ConfigurationController@show');
    Route::get('/configuration/delete/{configuration}', 'ConfigurationController@destroy');
    Route::post('/configuration', 'ConfigurationController@store');

    // E-Referral
    Route::get('/e_referral', 'EReferralController@index');
    Route::get('/e_referral/list', 'EReferralController@list');
    Route::get('/e_referral/new', 'EReferralController@create');
    Route::get('/e_referral/show/{erf_id}', 'EReferralController@show');
    Route::get('/e_referral/delete/{erf_id}', 'EReferralController@destroy');
    Route::get('/e_referral/list', 'EReferralController@list');
    Route::get('/e_referral/list_by_id/{erf_id}', 'EReferralController@listById');
    Route::get('/e_referral/listFormsFormated', 'EReferralController@listFormsFormated');
    Route::post('/e_referral', 'EReferralController@store');

    // Panel Lawyers
    Route::get('/panel_lawyers', 'PanelLawyersController@index');
    Route::get('/panel_lawyers/list', 'PanelLawyersController@list');
    Route::post('/panel_lawyers/get_closet_by_address', 'PanelLawyersController@getClosestByAddress');
    Route::post('/panel_lawyers/get_lat_lng', 'PanelLawyersController@getLatLngByAddress');

    //Comments
    // Route::post('/posts/{post}/comments', 'CommentsController@store') ;

    //posts
    // Route::post('/posts', 'PostsController@store');
    // Route::get('/posts/', 'PostsController@index');
    // Route::get('/posts/create', 'PostsController@create');
    // Route::get('/posts/{post}', 'PostsController@show');

    //Matter
    Route::get('/matter', 'MatterController@index');
    Route::get('/matter/list', 'MatterController@list');
    Route::get('/matter/listFormated', 'MatterController@listFormated');
    Route::get('/matter/listWithQuestionsFormated', 'MatterController@listWithQuestionsFormated');
    Route::get('/matter/listFormatedTrimmed', 'MatterController@listFormatedTrimmed');
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
    Route::get('/service', 'ServiceController@index')->name('serviceList');
    Route::get('/service/my_services', 'ServiceController@myServices');
    Route::get('/service/list', 'ServiceController@list');
    Route::get('/service/list_without_update/{days}', 'ServiceController@listWithoutUpdate');
    Route::get('/service/list_table', 'ServiceController@listTable');
    Route::get('/service/list_service_by_id/{sv_id}', 'ServiceController@listServiceById');
    Route::get('/service/list_services_sp/{sp_id}', 'ServiceController@listServicesSP');
    Route::get('/service/list_own_services/{sp_id}', 'ServiceController@listOwnServices');
    Route::get('/service/show/{sv_id}', 'ServiceController@show');
    Route::get('/service/delete/{sv_id}', 'ServiceController@destroy');
    Route::get('/service/new', 'ServiceController@create');
    Route::post('/service', 'ServiceController@store');
    Route::post('/service/general_settings', 'ServiceController@storeGeneralSettings');
    Route::post('/service/intake_options', 'ServiceController@storeIntakeOptions');
    Route::post('/service/client_eligibility', 'ServiceController@storeClientEligibility');
    Route::post('/service/legal_matter', 'ServiceController@storeLegalMatter');
    Route::post('/service/request_addition', 'ServiceController@sendRequestEmail');
    Route::post('/service/send_notification', 'ServiceController@sendNotification');

    // Outdated Services
    Route::get('/outdated_services', 'OutdatedServiceController@index');

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
    Route::post('/service_provider/listFormated', 'ServiceProvidersController@listFormated');
    Route::post('/service_provider/list_bookable_formated', 'ServiceProvidersController@listBookableFormated');
    Route::get('/service_provider/show/{sp_id}', 'ServiceProvidersController@show');
    Route::get('/service_provider/delete/{sp_id}', 'ServiceProvidersController@destroy');
    Route::get('/service_provider/new', 'ServiceProvidersController@create');
    Route::get('/service_provider/getById/{sp_id}', 'ServiceProvidersController@getById');
    Route::post('/service_provider', 'ServiceProvidersController@store');
    Route::post('/service_provider/set_sp', 'ServiceProvidersController@setServiceProvider');

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

    //Question Group
    Route::get('/question_group', 'QuestionGroupController@index');
    Route::get('/question_group/list', 'QuestionGroupController@list');
    Route::get('/question_group/list_formated', 'QuestionGroupController@listFormated');
    Route::get('/question_group/show/{qg_id}', 'QuestionGroupController@show');
    Route::get('/question_group/delete/{qg_id}', 'QuestionGroupController@destroy');
    Route::get('/question_group/new', 'QuestionGroupController@create');
    Route::post('/question_group', 'QuestionGroupController@store');

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
    Route::get('/service_booking_questions', 'QuestionController@serviceBookingQuestions');
    Route::get('/question', 'QuestionController@index');
    Route::get('/question/list', 'QuestionController@list');
    Route::get('/question/list_legal_matter', 'QuestionController@listLegalMatterQuestions');
    Route::get('/question/list_service_booking_questions', 'QuestionController@listServiceBookingQuestions');
    Route::get('/question/list_eligibility', 'QuestionController@listVulnerabilityQuestions');
    Route::get('/question/show/{qu_id}', 'QuestionController@show');
    Route::get('/question/delete/{qu_id}', 'QuestionController@destroy');
    Route::get('/question/new/{type?}', 'QuestionController@create');
    Route::post('/question', 'QuestionController@store');

    //Booking
    Route::get('/booking', 'BookingController@index');
    Route::get('/booking/next_bookings', 'BookingController@nextBookings');
    Route::get('/booking/show/{bk_id}', 'BookingController@show');
    Route::get('/booking/new', 'BookingController@create');
    Route::get('/booking/delete/{bo_id}', 'BookingController@destroy');
    Route::get('/booking/listDatesByDate/{year}/{month}/{sv_id}', 'BookingController@getServiceDatesByDate');
    Route::get('/booking/list', 'BookingController@list');
    Route::get('/booking/listCalendar', 'BookingController@listCalendar');
    Route::get('/booking/listCalendarBySp', 'BookingController@listCalendarBySp');
    Route::get('/booking/listCalendarByUser', 'BookingController@listCalendarByUser');
    Route::get('/booking/updateBooking/{booking_ref}/{date_time}', 'BookingController@updateBooking');
    Route::post('/booking/updateBookingByRef', 'BookingController@updateBooking');
    Route::post('/booking/updateBooking', 'BookingController@updateBookingDetails');
    Route::post('/booking', 'BookingController@store');
    Route::get('/booking/sendSmsReminder', 'BookingController@sendSmsReminder');
    Route::get('/booking/listLegalHelpBookings', 'BookingController@listLegalHelpBookings');
    Route::get('/booking/legalHelp', 'BookingController@legalHelp');

    //Booking Engine
    Route::get('/booking/services', 'BookingEngineController@index');
    Route::get('/booking/resources', 'BookingEngineController@resources');
    Route::get('/booking/services/days/{sv_id}', 'BookingEngineController@getServiceDays');
    Route::get('/booking/services/hours/{sv_id}', 'BookingEngineController@getServiceHours');
    Route::get('/booking/by_service_provider', 'BookingController@byServiceProvider');
    Route::get('/booking/service/{service_id}/booking/{start_date}/{end_date}', 'BookingEngineController@getServiceBookings');
    Route::get('/booking/service_provider/booking', 'BookingEngineController@getServiceBookingsBySP');
    Route::get('/booking/service/{sv_id}/getAvailability/{start_date}/{end_date}', 'BookingEngineController@getServiceAvailability');
    Route::patch('/booking', 'BookingController@updateBooking');
    Route::delete('/booking/{booking_id}', 'BookingEngineController@deleteBooking');
    Route::get('/booking/upcoming', 'BookingEngineController@nextBookings');
    Route::get('/booking/user', 'BookingEngineController@user');

    //Referral
    Route::get('/referral', 'ReferralController@index');
    Route::get('/referral/outbound', 'ReferralController@outbound');
    Route::get('/referral/show/{rf_id}', 'ReferralController@show');
    Route::get('/referral/new', 'ReferralController@create');
    Route::get('/referral/create/location', 'ReferralController@location');
    Route::get('/referral/create/details', 'ReferralController@details');
    Route::get('/referral/create/questions', 'ReferralController@questions');
    Route::get('/referral/create/review', 'ReferralController@review');
    Route::get('/referral/list', 'ReferralController@list');
    Route::get('/referral/listOutbound', 'ReferralController@listOutbound');
    Route::get('/referral/list_pager', 'ReferralController@listPager');
    Route::get('/referral/list_outbound_pager', 'ReferralController@listOutboundPager');
    Route::get('/referral/create/result', 'ReferralController@result');
    Route::post('/referral/create/result', 'ReferralController@result');
    Route::post('/referral', 'ReferralController@store');

    //Users
    Route::get('/user', 'UserController@index');
    Route::get('/user/information', 'UserController@information');
    Route::get('/user/show/{uid}', 'UserController@show');
    Route::get('/user/delete/{uid}', 'UserController@destroy');
    Route::get('/user/new', 'UserController@create');
    Route::get('/user/list', 'UserController@list');
    Route::get('/user/list_table', 'UserController@listTable');
    Route::get('/user/clearNotifications', 'UserController@clearNotifications');
    Route::post('/user/update', 'UserController@update');
    Route::post('/user', 'UserController@store');

    // Change Password
    Route::get('/change_password', 'UserController@changePassword');
    Route::post('/change_password', 'UserController@changeUserPassword');

    //SMS Templates
    Route::get('/sms_template', 'SmsTemplateController@index');
    Route::get('/sms_template/show/{st_id}', 'SmsTemplateController@show');
    Route::get('/sms_template/delete/{st_id}', 'SmsTemplateController@destroy');
    Route::get('/sms_template/new', 'SmsTemplateController@create');
    Route::get('/sms_template/list', 'SmsTemplateController@list');
    Route::post('/sms_template', 'SmsTemplateController@store');
    Route::get('/sms_template/getTemplateByServiceBookingId', 'SmsTemplateController@getTemplateByServiceBookingId');

    //Statistics
    Route::get('/statistic', 'StatisticController@index');
    Route::get('/statistic/list', 'StatisticController@listStatistics');

    //No Reply emails
    Route::get('/no_reply_emails', 'NoReplyEmailController@index');
    Route::get('/no_reply_emails/new', 'NoReplyEmailController@create');
    Route::get('/no_reply_emails/templates', 'NoReplyEmailController@indexTemplates');
    Route::get('/no_reply_emails/templates/new', 'NoReplyEmailController@createTemplate');
    Route::get('/no_reply_emails/templates/show/{te_id}', 'NoReplyEmailController@show');
    Route::get('/no_reply_emails/templates/delete/{te_id}', 'NoReplyEmailController@destroyTemplate');
    Route::get('/no_reply_emails/listAllTemplates', 'NoReplyEmailController@listAllTemplates');
    Route::get('/no_reply_emails/listAllLogRecords', 'NoReplyEmailController@listAllLogRecords');
    Route::get('/no_reply_emails/listTemplateById', 'NoReplyEmailController@listTemplateById');
    Route::get('/no_reply_emails/listAllTemplatesBySection', 'NoReplyEmailController@listAllTemplatesBySection');
    Route::get('/no_reply_emails/listAllMailBoxes', 'NoReplyEmailController@listAllMailBoxes');
    Route::get('/no_reply_emails/listTemplatesBySectionFormated', 'NoReplyEmailController@listAllTemplatesFormated');
    Route::post('/no_reply_emails', 'NoReplyEmailController@sendEmail');
    Route::post('/no_reply_emails/templates', 'NoReplyEmailController@saveTemplate');
    Route::get('/no_reply_emails/listAllLogRecordBySection', 'NoReplyEmailController@getAllLogRecordBySection');

    // Dashboard admin
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/dashboard/new', 'DashboardController@create');
    Route::get('/dashboard/show/{id}', 'DashboardController@show');
    Route::get('/dashboard/delete/{id}', 'DashboardController@destroy');
    Route::delete('/dashboard/{id}', 'DashboardController@destroy');
    Route::post('/dashboard', 'DashboardController@store');
    Route::post('/dashboard/updatePositions', 'DashboardController@updatePositions');

    // Service Booking
    Route::get('/service_booking', 'ServiceBookingController@index');
    Route::get('/service_booking/list', 'ServiceBookingController@list');
    Route::get('/service_booking/show/{sb_id}', 'ServiceBookingController@show');
    Route::get('/service_booking/new', 'ServiceBookingController@create');
    Route::post('/service_booking', 'ServiceBookingController@store');
    Route::get('/service_booking/delete/{sb_id}', 'ServiceBookingController@destroy');
    Route::post('/service_booking/activate_service', 'ServiceBookingController@activateService');

});
