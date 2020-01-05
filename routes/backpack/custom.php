<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    CRUD::resource('country', 'CountryCrudController');
    CRUD::resource('location', 'LocationCrudController');
    CRUD::resource('image', 'ImageCrudController');
    CRUD::resource('tour', 'TourCrudController');
    CRUD::resource('price', 'PriceCrudController');
    CRUD::resource('city', 'CityCrudController');
    CRUD::resource('preference', 'PreferenceCrudController');
    CRUD::resource('master_search_attribute', 'MasterSearchAttributeCrudController');

    //ajax
    Route::post('city/get-city-of-country', 'CityCrudController@getCityOfCountry')->name('admin.city.get-city-of-country');
    Route::post('location/get-location-of-country', 'LocationCrudController@getLocationOfCountry')->name('admin.location.get-location-of-country');
    Route::post('location/get-location-of-city', 'LocationCrudController@getLocationOfCity')->name('admin.location.get-location-of-city');
}); // this should be the absolute last line of this file