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

Route::get('/', "DashboardController@index")->name('dashboard');
Route::get('profile/', "ProfileController@index")->name('profile');


//Employess
Route::get('employee/', "EmployeeController@index")->name('employee');
Route::post('employee/store', "EmployeeController@store")->name('emp_store');
Route::get('employee/edit', "EmployeeController@edit")->name('emp_edit');
Route::delete('employee/destroy', "EmployeeController@destroy")->name('emp_remove');
Route::get('employee/list', "EmployeeController@list")->name('emp_list');


//Checklist
Route::get('employee/checklist', "ChecklistController@index")->name('checklist_employee');
Route::get('employee/checklist/complete/{id}', "ChecklistController@completeCheckList")->name('checklist_complete');
Route::post('employee/checklist/store', "ChecklistController@store")->name('checklist_store');
Route::delete('employee/checklist/destroy', "ChecklistController@destroy")->name('checklist_employee_remove');
Route::get('employee/yourchecklist', "CheckController@YourChecklist")->name('emp_yourchecklist_view');
Route::get('employee/listyourchecklist', "CheckController@listYourChecks")->name('emp_yourchecklist');


//Check
Route::post('employee/checkedit', "CheckController@store")->name('check_edit');
Route::get('employee/check', "CheckController@list")->name('check_list');

//Comment
Route::post('comment/store', "CommentController@store")->name('comment_store');
Route::get('comment/list', "CommentController@list")->name('comment_list');
Route::delete('comment/delete', "CommentController@destroy")->name('comment_remove');

//Admin
Route::get('Admin/', "AdminController@index")->name('admin');
Route::post('Admin/store', "AdminController@store")->name('admin_store');
Route::get('Admin/edit', "AdminController@edit")->name('admin_edit');
Route::delete('Admin/destroy', "AdminController@destroy")->name('admin_remove');
Route::get('Admin/list', "AdminController@list")->name('admin_list');
Route::get('Admin/profile', "AdminController@profile")->name('admin_profile');

//Tasks
Route::get('task/', "TaskController@index")->name('task');
Route::post('task/store', "TaskController@store")->name('task_store');
Route::get('task/list', "TaskController@list")->name('task_list');
Route::get('task/edit', "TaskController@edit")->name('task_edit');
Route::delete('task/destroy', "TaskController@destroy")->name('task_destroy');
Route::get('task/tree', "TaskController@tree")->name('task_tree');

//checklistsTemplates
Route::get('checklist/', "ChecklistTemplateController@index")->name('checklist');
Route::post('checklist/store', "ChecklistTemplateController@store")->name('template_store');
Route::get('checklist/edit', "ChecklistTemplateController@edit")->name('template_edit');
Route::delete('checklist/destroy', "ChecklistTemplateController@destroy")->name('template_destroy');
Route::get('checklist/array',"ChecklistTemplateController@returnChecklist")->name('template_array');

//Profiles
Route::get('profile/', "ProfileController@index")->name('profile');
Route::post('profile/store', "ProfileController@store")->name('profile_store');
Route::get('profile/list', "ProfileController@list")->name('profile_list');
Route::get('profile/edit', "ProfileController@edit")->name('profile_edit');
Route::delete('profile/destroy', "ProfileController@destroy")->name('profile_destroy');
Route::get('profile/checklist',"ProfileController@getCheckLists")->name('template_list');

//Groups
Route::post('Group/store', "GroupController@store")->name('group_store');
Route::get('Group/list', "GroupController@list")->name('group_list');
Route::delete('Group/destroy', "GroupController@destroy")->name('group_delete');

//Sites
Route::get('site/list', "SiteController@list")->name('site_list');

//Auth
Auth::routes();
Route::post('/clearnot','HomeController@clearAllNot')->name('clrnot');
Route::post('/updtnot','HomeController@updateNotification')->name('updnot');
Route::get('/getflag','HomeController@getFlagNot')->name('getflagnoti');
Route::get('/logout','HomeController@logout')->name('logout');
Route::get('/getuser','HomeController@getUser')->name('getuser');
Route::get('/getname','HomeController@getName')->name('getname');
Route::get('/getperm','HomeController@getPerm')->name('getperm');
Route::get('/getnotifications','HomeController@getNotifications')->name('getnoti');
Route::get('/routes','HomeController@getRoutes')->name('get_routes');
