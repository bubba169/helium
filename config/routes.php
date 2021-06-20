<?php

Route::prefix('/admin')
    ->namespace('Helium\\Http\\Controllers')
    ->middleware('web', 'auth', \Helium\Http\Middleware\LoadExtension::class)
    ->group(function () {
        Route::view('/', 'helium::dashboard')->name('helium.home');
        Route::post('/entities/form-section', 'EntitiesController@section')->name('helium.entity.form-section');
        Route::get('/entities/{type}', 'EntitiesController@list')->name('helium.entity.list');
        Route::get('/entities/{type}/form/{form}/{id?}', 'EntitiesController@form')->name('helium.entity.form');
        Route::post('/entities/action/{id?}', 'EntitiesController@action')->name('helium.entity.action');
    });
