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

//Views
Route::get('/', "DashboardController@index")->name('dashboard');
Route::get('profile/', "ProfileController@index")->name('profile');
Route::get('employee/', "EmployeeController@index")->name('employee');
Route::get('task/', "TaskController@index")->name('task');
Route::get('Admin/', "AdminController@index")->name('admin');
Route::get('employee/yourtasks', "CheckController@YourTasks")->name('yourtasks');


//Employess
Route::post('employee/store', "EmployeeController@store")->name('employee_store');
Route::get('employee/list', "EmployeeController@list")->name('employee_list');
Route::delete('employee/destroy', "EmployeeController@destroy")->name('employee_destroy');


//Checklist
Route::post('employee/checklist/store', "ChecklistController@store")->name('checklist_store');
Route::get('employee/checklist', "ChecklistController@list")->name('checklist_list');
Route::delete('employee/checklist/destroy', "ChecklistController@destroy")->name('checklist_destroy');

Route::get('employee/checklist/complete/{id}', "ChecklistController@completeCheckList")->name('checklist_complete');

Route::get('employee/listyourchecklist', "CheckController@listYourChecks")->name('emp_yourchecklist');


//Check
Route::post('employee/checkedit', "CheckController@store")->name('check_store');
Route::get('employee/check', "CheckController@list")->name('check_list');


//Comment
Route::post('comment/store', "CommentController@store")->name('comment_store');
Route::get('comment/list', "CommentController@list")->name('comment_list');
Route::delete('comment/destroy', "CommentController@destroy")->name('comment_destroy');

//Admin
Route::post('Admin/store', "AdminController@store")->name('admin_store');
Route::get('Admin/list', "AdminController@list")->name('admin_list');
Route::delete('Admin/destroy', "AdminController@destroy")->name('admin_destroy');

//Tasks
Route::post('task/store', "TaskController@store")->name('task_store');
Route::get('task/list', "TaskController@list")->name('task_list');
Route::delete('task/destroy', "TaskController@destroy")->name('task_destroy');
Route::get('task/tree', "TaskController@tree")->name('task_tree');

//checklistsTemplates
Route::get('template/checklist',"ChecklistTemplateController@list")->name('template_list');
Route::post('template/store', "ChecklistTemplateController@store")->name('template_store');
Route::get('template/edit', "ChecklistTemplateController@edit")->name('template_edit');
Route::delete('template/destroy', "ChecklistTemplateController@destroy")->name('template_destroy');
Route::get('template/tree',"ChecklistTemplateController@tree")->name('template_tree');


//Profiles

Route::post('profile/store', "ProfileController@store")->name('profile_store');
Route::get('profile/list', "ProfileController@list")->name('profile_list');
Route::delete('profile/destroy', "ProfileController@destroy")->name('profile_destroy');


//Groups
Route::post('Group/store', "GroupController@store")->name('group_store');
Route::get('Group/list', "GroupController@list")->name('group_list');
Route::delete('Group/destroy', "GroupController@destroy")->name('group_destroy');

//Sites
Route::get('site/list', "SiteController@list")->name('site_list');

//Notifications
Route::post('/clearnot','HomeController@clearAllNot')->name('clrnot');
Route::post('/updtnot','HomeController@updateNotification')->name('notification_store');
Route::get('/getnotifications','HomeController@getNotifications')->name('notification_list');

//Auth
Auth::routes();

Route::get('/getflag','HomeController@getFlagNot')->name('getflagnoti');
Route::get('/logout','HomeController@logout')->name('logout');
Route::get('/getuser','HomeController@getUser')->name('getuser');
Route::get('/getname','HomeController@getName')->name('getname');
Route::get('/getperm','HomeController@getPerm')->name('getperm');
Route::get('/routes','HomeController@getRoutes')->name('get_routes');
