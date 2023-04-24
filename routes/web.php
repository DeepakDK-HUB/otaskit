<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['namespace' => 'App\Http\Controllers\Admin', 'middleware' => 'auth'], function () {

    Route::post('employee/check-mail', 'EmployeeController@postCheckMail')->name('employee.check-mail');
    Route::resource('employee', 'EmployeeController');

    Route::get('task/assign-task', 'TaskController@assignTask')->name('task.assign-task');
    Route::post('task/add-assign-task', 'TaskController@postAssignTask')->name('add.assign-task');
    Route::resource('task', 'TaskController');
});