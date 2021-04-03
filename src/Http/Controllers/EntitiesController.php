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

    public function edit(string $type, int $id, EntityConfig $configLoader, Request $request)
    {
        $config = $configLoader->getConfig($type);

        if ($request->isMethod('post')) {
            $handler = Arr::get($config, 'form.actions.' . $request->input('helium_action') . '.handler');
            if ($handler) {
                $result = app()->call($handler . '@handle', ['config' => $config]);
                if ($result) {
                    return $result;
                }

                //Session::flash();
            }
        }

        return view($config['form']['view'], [
            'config' => $config,
            'entry' => $config['model']::find($id),
        ]);
    }
}
