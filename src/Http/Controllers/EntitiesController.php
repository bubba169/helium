<?php

namespace Helium\Http\Controllers;

use Helium\Config\Entity;
use Illuminate\View\View;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Helium\Support\EntityConfig;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EntitiesController extends HeliumController
{
    /**
     * Renders the list
     */
    public function list(EntityConfig $configLoader, Request $request, string $type) : View
    {

        dd(new Entity($type));
        $config = $configLoader->getConfig($type);



        $query = app()->call($config['table']['handler'], ['config' => $config]);

        // Apply the search query
        if (!empty($config['table']['search']['handler'])) {
            $query = app()->call($config['table']['search']['handler'], [
                'query' => $query,
                'searchConfig' => $config['table']['search']
            ]);
        }

        // Apply the filter queries
        foreach ($config['table']['filters'] as $filter) {
            $query = app()->call($filter['handler'], [
                'query' => $query,
                'filterConfig' => $filter
            ]);
        }

        $entries = $query->paginate(50);

        return view($config['table']['view'], [
            'config' => $config,
            'entries' => $entries,
            'filtersOpen' => count(array_filter($request->except('search'))),
        ]);
    }

    /**
     * Renders a form using the config
     */
    public function form(EntityConfig $configLoader, string $type, string $form, ?int $id = null) : View
    {
        $config = $configLoader->getConfig($type);
        $formConfig = Arr::get($config, "forms.$form");
        $entry = null;

        // If the form config is not found then 404
        if (!$formConfig) {
            throw new NotFoundHttpException();
        }

        // If an id is given load the entry
        if ($id) {
            $entry = $config['model']::find($id);

            // If the entry can't be found then 404
            if (!$entry) {
                throw new NotFoundHttpException();
            }
        }

        return view($config['forms'][$form]['view'], [
            'config' => $config,
            'form' => $formConfig,
            'entry' => $entry,
        ]);
    }

    /**
     * Processes a form action using the assigned request type
     */
    public function store(EntityConfig $configLoader, string $type, string $form, ?int $id = null)
    {
        $config = $configLoader->getConfig($type);
        $action = request()->input('helium_action');
        $requestType = Arr::get($config, "forms.$form.actions.$action.handler");

        if ($requestType) {
            $request = app($requestType, [
                'entityConfig' => $config,
                'formName' => $form,
                'entryId' => $id
            ]);
            return $request->handle();
        }
    }
}
