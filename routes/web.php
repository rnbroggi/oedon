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

    // Home y perfil
    Route::group(['middleware' => ['can:home']], function () {
        Route::get('/', 'HomeController@index')->name('home');
        Route::get('/profile', 'ProfileController@edit')->name('profile');
        Route::post('/profile', 'ProfileController@update')->name('profile.update');
    });

    // Usuarios y permisos
    Route::group(['middleware' => ['can:crud usuarios']], function () {
        Route::resource('users', 'UserController')->middleware('can:crud usuarios');
        Route::resource('roles', 'RoleController')->middleware('can:crud roles');
        Route::resource('permissions', 'PermissionController')->middleware('can:crud permisos');
        Route::get('/impersonate/{user_id}', 'UserController@impersonate')->name('impersonate')->middleware('can:impersonate');
    });
    Route::get('/impersonate_leave', 'UserController@impersonateLeave')->name('impersonate_leave');
    
    // Auditoria
    Route::get('audits', 'AuditController@index')->name('audits.index')->middleware('can:audits-logs');

    // Veterinarias
    Route::resource('veterinarias', 'VeterinariaController')->middleware('can:crud usuarios');

    // Clientes
    Route::get('clientes', 'ClienteController@index')->middleware('can:view clientes');
});

// locale Route
// Route::get('lang/{locale}',[LanguageController::class,'swap']);
