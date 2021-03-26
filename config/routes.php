<?php

Route::prefix('/admin')
    ->namespace('Helium\\Http\\Controllers')
    ->middleware('web', 'auth')
    ->group(function () {
        Route::view('/', 'helium::dashboard')->name('admin.home');
        Route::get('/entities/{type}', 'EntitiesController@list')->name('entity.list');
    });
