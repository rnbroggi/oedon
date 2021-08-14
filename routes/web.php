<?php

use App\Http\Controllers\LanguageController;
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


// Route url
Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::group(['middleware' => ['auth']], function () {

    Route::group(['middleware' => ['can:home']], function () {
        Route::get('/', 'HomeController@index')->name('home');
        Route::get('/profile', 'ProfileController@edit')->name('profile');
        Route::post('/profile', 'ProfileController@update')->name('profile.update');
    });

    Route::group(['middleware' => ['can:abm usuarios']], function () {
        Route::resource('users', 'UserController');
        Route::resource('roles', 'RoleController');
        Route::resource('permissions', 'PermissionController');
        Route::get('/impersonate/{user_id}', 'UserController@impersonate')->name('impersonate');

        // Auditoria
        Route::get('audits', 'AuditController@index')->name('audits.index');
    });
    Route::get('/impersonate_leave', 'UserController@impersonateLeave')->name('impersonate_leave');

});

// locale Route
// Route::get('lang/{locale}',[LanguageController::class,'swap']);
