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
Route::get('employee/create', "EmployeeController@create")->name('emp.create');
Route::post('employee/store', "EmployeeController@store")->name('emp.store');
Route::get('employee/edit/{id}', "EmployeeController@edit")->name('emp.edit');
Route::delete('employee/remove/{id}', "EmployeeController@destroy")->name('emp.remove');
Route::post('employee/update',"EmployeeController@Update")->name('emp.update');
Route::get('employee/list',"EmployeeController@list")->name('emp.list');

//Tasks
Route::get('task/', "TaskController@index")->name('task');
Route::post('task/store', "TaskController@store")->name('task.store');
Route::get('task/list',"TaskController@list")->name('task.list');
Route::get('task/edit',"TaskController@edit")->name('task.edit');
Route::delete('task/destroy', "TaskController@destroy")->name('task.destroy');


Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::get('/logout','HomeController@logout')->name('logout');
Route::get('/getname','HomeController@getName')->name('getname');
