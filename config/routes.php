<?php

Route::prefix('/admin')
    ->namespace('Helium\\Http\\Controllers')
    ->middleware('web', 'auth')
    ->group(function () {
        Route::view('/', 'helium::dashboard')->name('admin.home');
        Route::get('/{entityType}/', 'EntitiesController@index')->name('entity.index');
        Route::get('/{entityType}/edit/{id}', 'EntitiesController@edit')->name('entity.edit');
        Route::post('/{entityType}/edit/{id}', 'EntitiesController@save')->name('entity.update');
        Route::get('/{entityType}/create', 'EntitiesController@create')->name('entity.create');
        Route::post('/{entityType}/create', 'EntitiesController@save')->name('entity.store');
    });
