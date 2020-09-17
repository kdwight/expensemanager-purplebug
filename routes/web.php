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

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', 'HomeController@index')->name('home');
    Route::get('profile', 'HomeController@profile');
    Route::put('profile/password', 'HomeController@updatePassword');

    Route::get('categories/list', 'CategoryController@list');
    Route::get('expenses/records', 'ExpenseController@records');
    Route::resource('expenses', 'ExpenseController')->except(['show', 'create', 'edit']);

    Route::middleware(['is_admin'])->group(function () {
        Route::get('categories/records', 'CategoryController@records');
        Route::resource('categories', 'CategoryController')->except(['show', 'create', 'edit']);

        Route::get('roles/list', 'RoleController@list');
        Route::get('roles/records', 'RoleController@records');
        Route::resource('roles', 'RoleController')->except(['show', 'create', 'edit']);

        Route::get('users/records', 'UserController@records');
        Route::resource('users', 'UserController')->except(['show', 'create', 'edit']);
    });
});

Auth::routes([
    'register' => false,
    'reset' => false
]);
