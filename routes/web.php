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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/parents', 'SiteController@parents');
Route::get('/home', 'HomeController@index')->name('home');
Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function(){
	Route::get('/', 'AdminController@index');
	Route::get('/spatie', 'AdminController@spatie');
	Route::get('/create-user', 'AdminController@create_user');

	Route::any('/classes/{id?}', 'SchoolClassController@index');
	Route::any('/sections/{id?}', 'SectionController@index');
	Route::any('/transport/transporters/{id?}', 'TransportController@transporters');
	Route::any('/transport/routes/{id?}', 'TransportController@routes');
	Route::any('/transport/conveyances/{transporter_id}', 'TransportController@conveyances');
	Route::any('/department/{id?}', 'DepartmentController@index');
	Route::any('/designation/{id?}', 'DesignationController@index');
	Route::any('/staff/{id?}', 'StaffController@index');
	Route::any('/fee-type/{id?}', 'FeeStructureController@fee_type');
	Route::any('/fee-structure/{id?}', 'FeeStructureController@index');
	Route::any('/subjects/{id?}', 'SubjectController@index');

	Route::any('/manage-student/{id?}', 'AdminController@manage_student');
	Route::get('/student-list/{id?}', 'AdminController@student_list');
	Route::get('/student-action/', 'AdminController@student_action');
	Route::post('/student-action-save/', 'AdminController@student_action_save');
	Route::get('/student-view/{id?}', 'AdminController@student_view');
	Route::any('/student-fee/{id}', 'AdminController@student_fee');
	Route::get('/student-fee-receipt/{id}', 'AdminController@student_fee_receipt');

	Route::any('/attendance/', 'AttendanceController@index');
	Route::any('/attendance-list/', 'AttendanceController@attendance_list');
	Route::any('/attendance-insight/', 'AttendanceController@attendance_insight');
	Route::any('/homework/{id?}', 'HomeworkController@index');
	Route::any('/homework-list/', 'HomeworkController@homework_list');
	Route::any('/homework-insight/', 'HomeworkController@homework_insight');
	Route::any('/sms/{id?}', 'SmsController@index');
	Route::get('/sms-list/{id?}', 'SmsController@sms_list');
	Route::get('/sms-details/{id}', 'SmsController@sms_details');
	Route::get('/sms-cron', 'SmsController@cron');
	Route::get('/sms-promotional-list', 'SmsController@sms_promotional_list');
	Route::any('/sms-promotional-add', 'SmsController@sms_promotional_add');
	Route::get('/sms-promotional-details/{id}', 'SmsController@sms_promotional_details');


	//ajax
	
});
Route::any('/admin/ajax/sections/{id?}', 'AjaxController@sections');
Route::any('/admin/ajax/count-chars/', 'AjaxController@count_chars');

Route::get('/student/login','Auth\StudentLoginController@showLoginForm');
Route::post('/student/login', 'Auth\StudentLoginController@login');
Route::get('/student/logout/', 'Auth\StudentLoginController@logout');
Route::group(['prefix' => 'student', 'middleware' => ['student']], function(){
	
	Route::get('/', 'StudentController@index');
	Route::get('/homework', 'ParentsController@homework');
});
Route::group(['prefix' => 'admin', 'middleware' => ['student']], function(){
	Route::get('/parents/homework', 'ParentsController@homework');
	Route::get('/parents/attendance', 'ParentsController@attendance');
});