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
        Route::resource('users', 'UserController')->middleware('can:crud usuarios', ['except' => ['index']]);

        Route::get('/change_user_status/{user}', 'UserController@changeStatus')->name('user.change_status');

        // Roles
        Route::resource('roles', 'RoleController')->middleware('can:crud roles');

        // Permisos
        Route::resource('permissions', 'PermissionController')->middleware('can:crud permisos');

        // Impersonate
        Route::get('/impersonate/{user_id}', 'UserController@impersonate')->name('impersonate')->middleware('can:impersonate');
    });
    Route::get('/impersonate_leave', 'UserController@impersonateLeave')->name('impersonate_leave');

    // Auditoria
    Route::get('audits', 'AuditController@index')->name('audits.index')->middleware('can:audits-logs');

    // Veterinarias
    Route::resource('veterinarias', 'VeterinariaController')->middleware('can:crud usuarios');

    // Clientes
    Route::get('clientes', 'ClienteController@index')->name('clientes.index')->middleware('can:view clientes');

    // Razas
    Route::resource('razas', 'RazaController')->middleware('can:crud razas');

    // Mascotas
    Route::resource('mascotas', 'MascotaController'); //Middlewares en el controlador

    Route::patch('update_picture/{mascota}', 'MascotaController@updatePicture')->name('mascotas.update_picture')->middleware('can:crud mascotas');

    // Visitas
    Route::resource('visitas', 'VisitaController'); //Middlewares en el controlador

    Route::get('get_veterinarios/{mascota_id}', 'VisitaController@getVeterinarios')->name('visitas.get_veterinarios');

    // Archivos visitas
    Route::group(['prefix' => 'visitas', 'middleware' => ['can:view visitas']], function () {
        Route::get('single_file_download/{file}', 'VisitaController@singleFileDownload')->name('visitas.singleFileDownload');
        Route::get('delete_single_file/{file}', 'VisitaController@deleteSingleFile')->name('visitas.deleteSingleFile')->middleware('can:crud visitas');
        Route::get('multiple_file_download/{visita}', 'VisitaController@multipleFileDownload')->name('visitas.multipleFileDownload');
    });
});

// locale Route
// Route::get('lang/{locale}',[LanguageController::class,'swap']);
