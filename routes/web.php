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
Route::get('task/', "TaskController@index")->name('task');

<<<<<<< HEAD
//Employee
Route::get('employee/', "EmployeeController@index")->name('employee');
Route::get('employee/create', "EmployeeController@create")->name('emp.create');
Route::post('/', "EmployeeController@store")->name('emp.store');
Route::get('employee/edit/{id}', "EmployeeController@edit")->name('emp.edit');
Route::delete('employee/remove/{id}', "EmployeeController@destroy")->name('emp.remove');
=======
//User
Route::get('employee/', "EmployeeController@index")->name('employee');
Route::get('employee/create', "EmployeeController@create")->name('emp.create');
Route::post('/store', "EmployeeController@store")->name('emp.store');
Route::get('employee/edit/{id}', "EmployeeController@edit")->name('emp.edit');
Route::delete('employee/remove/{id}', "EmployeeController@destroy")->name('emp.remove');
Route::post('/',"EmployeeController@Update")->name('emp.update');
Route::get('/list',"EmployeeController@list")->name('emp.list');
>>>>>>> Users
