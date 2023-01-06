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
    En laravel el orden de ejecucion de las rutas es de arriba a abajo => (/)
*/

Route::get('/', function () {
    return view('welcome');
});






Route::get('usuarios/', 'UserController@index')
            ->name('users');


Route::get('usuarios/{user}','UserController@show')//los resultados de las rutas se pasan a Controllers y se referencian con una @
            ->where('user', '[0-9]+')
            ->name('users.show');

Route::get('usuarios/nuevo', 'UserController@create')
            ->name('users.create');

Route::post('/usuarios', 'UserController@store');

Route::get('saludo/{name}/{nickname?}', 'WelcomeUserController');//en el controller, con el metodo __invoke llamamos a la funcion sin necesidad de @
//añadiendo '?' al parametro lo hacemos opcional









Route::get('/inicio', function () {//ruta en forma primitiva. sin mover la logica interna a controllers
    return 'Hola Mundo';
});
