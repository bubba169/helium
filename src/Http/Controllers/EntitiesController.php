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
    public function list(Request $request, string $type) : View
    {
        $entity = new Entity($type);
        $query = app()->call($entity->table->query, ['entity' => $entity]);

        if ($search = $entity->table->search) {
            $query = app()->call($search->handler, [
                'query' => $query,
                'filter' => $search
            ]);
        }

        foreach ($entity->table->filters as $filter) {
            $query = app()->call($filter->handler, [
                'query' => $query,
                'filter' => $filter
            ]);
        }

        $entries = $query->paginate(50);

        return view($entity->table->view, [
            'entity' => $entity,
            'entries' => $entries,
            'filtersOpen' => count(array_filter($request->except('search'))),
        ]);
    }

    /**
     * Renders a form using the config
     */
    public function form(string $type, string $form, ?int $id = null) : View
    {
        $config = new Entity($type);
        $form = $config->forms[$form];
        $entry = null;

        // If the form config is not found then 404
        if (!$form) {
            throw new NotFoundHttpException();
        }

        // If an id is given load the entry
        if ($id) {
            $entry = $config->model::find($id);

            // If the entry can't be found then 404
            if (!$entry) {
                throw new NotFoundHttpException();
            }
        }

        return view($form->view, [
            'config' => $config,
            'form' => $form,
            'entry' => $entry,
        ]);
    }

    /**
     * Processes a form action using the assigned request type
     */
    public function store(string $type, string $form, ?int $id = null)
    {
        $config = new Entity($type);
        $form = $config->forms[$form];

        $actionName = request()->input('helium_action');
        $requestType = $form->actions[$actionName]->request;

        if ($requestType) {
            $request = app($requestType, [
                'entity' => $config,
                'form' => $form,
                'entryId' => $id
            ]);
            return $request->handle();
        }
    }
}
