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
Route::get('employee/', "EmployeeController@index")->name('employee');
Route::get('profile/', "ProfileController@index")->name('profile');
Route::get('task/', "TaskController@index")->name('task');

//User
Route::get('employee/create', "EmployeeController@create")->name('emp.create');
Route::post('/', "EmployeeController@store")->name('emp.store');
Route::get('employee/edit/{Employee}', "EmployeeController@edit")->name('emp.edit');
Route::delete('employee/remove/{Employee}', "EmployeeController@destroy")->name('emp.remove');