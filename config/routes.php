<?php

Route::prefix('/admin')
    ->namespace('Helium\\Http\\Controllers')
    ->middleware('web', 'auth', \Helium\Http\Middleware\LoadExtension::class)
    ->group(function () {
        Route::view('/', 'helium::dashboard')->name('helium.home');
        Route::get('/entities/{type}', 'EntitiesController@list')->name('helium.entity.list');
        Route::get('/entities/{type}/{form}/{id?}', 'EntitiesController@form')->name('helium.entity.form');
        Route::post('/entities/{type}/{form}/{id?}', 'EntitiesController@store')->name('helium.entity.action');
    });
