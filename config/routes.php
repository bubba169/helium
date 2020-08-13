<?php

Route::prefix('/admin/manage')
    ->namespace('Helium\\Http\\Controllers')
    ->middleware('web', 'auth')
    ->group(function () {
        Route::get('/{entityType}/edit/{id}', 'EntitiesController@edit');
    });
