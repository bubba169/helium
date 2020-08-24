<?php

Route::prefix('/admin/manage')
    ->namespace('Helium\\Http\\Controllers')
    ->middleware('web', 'auth')
    ->group(function () {
        Route::get('/{entityType}/', 'EntitiesController@index')->name('entity.index');
        Route::get('/{entityType}/edit/{id}', 'EntitiesController@edit')->name('entity.edit');
        Route::post('/{entityType}/edit/{id}', 'EntitiesController@edit')->name('entity.save');
    });
