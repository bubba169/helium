<?php

Route::prefix('/admin')
    ->namespace('Helium\\Http\\Controllers')
    ->middleware('web', 'auth', \Helium\Http\Middleware\LoadExtension::class)
    ->group(function () {
        Route::view('/', 'helium::dashboard')->name('helium.home');
        Route::get('/entities/{type}', 'EntitiesController@list')->name('helium.entity.list');
        Route::get('/entities/{type}/{id}/edit', 'EntitiesController@edit')->name('helium.entity.edit');
        Route::post('/entities/{type}/{id}/edit', 'EntitiesController@store')->name('helium.entity.store');
    });
