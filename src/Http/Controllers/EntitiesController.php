<?php

namespace Helium\Http\Controllers;

use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EntitiesController extends HeliumController
{
    public function list($modelName)
    {
        $default = [
            'view' => 'helium::table'
        ];
        $config = config('helium.entities.' . $modelName);

        if (empty($config)) {
            throw new NotFoundHttpException();
        }

        // Normalise and merge with the defaults
        $config['table']['columns'] = array_normalise_keys($config['table']['columns']);
        $config = array_merge_deep($default, $config);

        return view($config['view'], [
            'config' => $config,
            'entries' => $config['model']::all(),
            'title' => $config['table']['title'] ?? Str::plural(basename($config['model']))
        ]);
    }
}
