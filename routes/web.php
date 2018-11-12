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
Route::post('employee/store', "EmployeeController@store")->name('emp.store');
Route::get('employee/edit', "EmployeeController@edit")->name('emp.edit');
Route::delete('employee/destroy', "EmployeeController@destroy")->name('emp.remove');
Route::get('employee/list', "EmployeeController@list")->name('emp.list');

//Tasks
Route::get('task/', "TaskController@index")->name('task');
Route::post('task/store', "TaskController@store")->name('task.store');
Route::get('task/list', "TaskController@list")->name('task.list');
Route::get('task/edit', "TaskController@edit")->name('task.edit');
Route::delete('task/destroy', "TaskController@destroy")->name('task.destroy');

//checklistsTemplates
Route::get('checklist/', "ChecklistTemplateController@index")->name('checklist');
Route::post('checklist/store', "ChecklistTemplateController@store")->name('checklist.store');
Route::get('checklist/list', "ChecklistTemplateController@list")->name('checklist.list');
Route::get('checklist/edit', "ChecklistTemplateController@edit")->name('checklist.edit');
Route::delete('checklist/destroy', "ChecklistTemplateController@destroy")->name('checklist.destroy');

//Profiles
Route::get('profile/', "ProfileController@index")->name('profile');
Route::post('profile/store', "ProfileController@store")->name('profile.store');
Route::get('profile/list', "ProfileController@list")->name('profile.list');
Route::get('profile/edit', "ProfileController@edit")->name('profile.edit');
Route::delete('profile/destroy', "ProfileController@destroy")->name('profile.destroy');

