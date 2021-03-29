<?php

namespace Helium\Http\Controllers;

use Helium\Support\EntityConfig;

class EntitiesController extends HeliumController
{
    public function list(string $modelName, EntityConfig $configLoader)
    {
        $config = $configLoader->getConfig($modelName);

        return view($config['table']['view'], [
            'config' => $config,
            'entries' => $config['model']::all(),
        ]);
    }
}
