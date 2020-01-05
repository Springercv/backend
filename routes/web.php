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
    return redirect()->route('backpack.dashboard');
});

Route::group(
    [
        'middleware' => 'web',
        'prefix' => config('backpack.base.route_prefix'),
    ],
    function () {
        // Registration Routes...
        Route::get('register', function () {
            return abort(404);
        })->name('backpack.auth.register');

        Route::post('register',  function () {
            return abort(404);
        })->name('backpack.auth.register.post');
    }
);