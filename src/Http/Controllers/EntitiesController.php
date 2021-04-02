<?php

namespace Helium\Http\Controllers;

use Helium\Support\EntityConfig;

class EntitiesController extends HeliumController
{
    public function list(string $type, EntityConfig $configLoader)
    {
        $config = $configLoader->getConfig($type);

        return view($config['table']['view'], [
            'config' => $config,
            'entries' => $config['model']::all(),
        ]);
    }

    public function edit(string $type, int $id, EntityConfig $configLoader)
    {
        $config = $configLoader->getConfig($type);

        return view($config['form']['view'], [
            'config' => $config,
            'entry' => $config['model']::find($id),
        ]);
    }
}
