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


Route::get('/cron', 'CronController@index');
Route::get('/access', 'CronController@getAccessToken');
Route::get('/refreshtoken', 'CronController@getRefreshAccessToken');
Route::get('/portal', 'CronController@getPortal');
Route::get('/projects', 'CronController@projects');
Route::get('/all_users', 'CronController@allUsers');
Route::get('/users', 'CronController@users');
Route::get('/project_user', 'CronController@projectUsers');
Route::get('/tasklists/{internal}', 'CronController@taskLists');
Route::get('/tasklists/{external}', 'CronController@taskLists');
Route::get('/tasks/', 'CronController@tasks');
Route::get('/bugs/', 'CronController@bugs');
Route::get('/subtasks/', 'CronController@subTasks');
Route::get('/logs/{users_list}/{view_type}/{bill_status}/{date}/{component_type}', 'CronController@logs');
Route::get('/sendMail', 'CronController@sendWeeklyMail');



/*
* Route for Admin
*/
Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {	
 	Route::get('/dashboard', 'AdminController@index'); //route to fetch all record

 	/*Route::post('/getuser','AdminController@getUser');
 	Route::get('/log','AdminController@logDetail');*/
 	Route::get('/user_log','AdminController@userLog');
 	Route::get('/employee','AdminController@employee_list');
 	Route::post('/time_log','AdminController@getTimeLog');
 	Route::get('/time_log1','AdminController@getTimeLog1');
 	Route::post('/generateSheet','AdminController@generateSheet');
});
