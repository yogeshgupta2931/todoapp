<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'App\Http\Controllers\TodoController@index')->name('home');
    Route::post('/todo/store', 'App\Http\Controllers\TodoController@store')->name('todo.store');
    Route::get('/todo/edit/{id}', 'App\Http\Controllers\TodoController@edit')->name('todo.edit');
    Route::post('/todo/update/{id}', 'App\Http\Controllers\TodoController@update')->name('todo.update');
    Route::post('/todo/change_status/{id}', 'App\Http\Controllers\TodoController@change_status')->name('todo.change_status');
    Route::post('/todo/delete/{id}', 'App\Http\Controllers\TodoController@delete')->name('todo.delete');
    Route::post('/todo/restore/{id}', 'App\Http\Controllers\TodoController@restore')->name('todo.restore');
});

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/users', 'App\Http\Controllers\AdminController@users_list')->name('admin.users.list');
    Route::get('/users/{id}', 'App\Http\Controllers\AdminController@users_todos')->name('admin.user.todos');

    Route::post('/users/{user_id}/todo/create', 'App\Http\Controllers\AdminController@create_todo')->name('admin.user.todo.create');
    Route::get('/users/todo/edit/{id}', 'App\Http\Controllers\AdminController@edit_todo')->name('admin.user.todo.edit');
    Route::post('/users/todo/update/{id}', 'App\Http\Controllers\AdminController@update_todo')->name('admin.user.todo.update');
    Route::post('/users/todo/change_status/{id}', 'App\Http\Controllers\AdminController@change_status_todo')->name('admin.user.todo.change_status');
    Route::post('/users/todo/delete/{id}', 'App\Http\Controllers\AdminController@delete_todo')->name('admin.user.todo.delete');
    Route::post('/users/todo/restore/{id}', 'App\Http\Controllers\AdminController@restore_todo')->name('admin.user.todo.restore');
});
