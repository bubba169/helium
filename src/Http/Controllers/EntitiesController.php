<?php

namespace Helium\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Helium\Support\EntityConfig;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;

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

    public function add(string $type, EntityConfig $configLoader)
    {
        $config = $configLoader->getConfig($type);

        return view($config['form']['view'], [
            'config' => $config,
        ]);
    }

    public function edit(string $type, int $id, EntityConfig $configLoader)
    {
        $config = $configLoader->getConfig($type);

        return view($config['forms']['edit']['view'], [
            'config' => $config,
            'form' => $config['forms']['edit'],
            'entry' => $config['model']::find($id),
        ]);
    }

    public function store(EntityConfig $configLoader, Request $request, string $type, ?int $id = null)
    {
        $config = $configLoader->getConfig($type);

        $handler = Arr::get($config, 'forms.' . $request->input('helium_form') . '.actions.' . $request->input('helium_action') . '.handler');
        if ($handler) {
            return app()->call($handler, ['config' => $config]);
        }
    }
}
