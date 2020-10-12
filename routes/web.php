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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Speciality

Route::get('/specialties', 'SpecialtyController@index'); // Devuelve una vista con el listado de especialidades
Route::get('/specialties/create', 'SpecialtyController@create'); //Vista con el formulario de refistro
Route::get('/specialties/{specialty}/edit', 'SpecialtyController@edit');// Devuelve un formulario de edición con los datos de la especialidad seleccionada
Route::post('/specialties', 'SpecialtyController@store'); // Envío del formulario de registro
Route::put('/specialties/{specialty}', 'SpecialtyController@update');// Actualiza los datos de la especialidad
Route::delete('/specialties/{specialty}', 'SpecialtyController@destroy');// Elimina especialidad

//Doctores
Route::resource('doctors', 'DoctorController');

//Pacientes
Route::resource('patients', 'PatientController');
