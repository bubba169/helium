<?php

namespace Helium\Http\Controllers;

use DateTime;
use Helium\Config\Entity;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
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
            $query = app()->call($search->filterHandler, [
                'query' => $query,
                'filter' => $search
            ]);
        }

        foreach ($entity->table->filters as $filter) {
            $query = app()->call($filter->filterHandler, [
                'query' => $query,
                'filter' => $filter
            ]);
        }

        $entries = $query->paginate(50);

        return view($entity->table->view, [
            'entity' => $entity,
            'entries' => $entries,
            'filtersOpen' => count(array_filter($request->except(['search', 'sort', 'page']))),
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
     * Renders a form section for a repeater
     */
    public function section(Request $request) : View
    {
        $config = new Entity($request->input('entity'));
        $form = $config->forms[$request->input('form')];

        // If the form config is not found then 404
        if (!$form) {
            throw new NotFoundHttpException();
        }

        $field = $form->getField($request->input('field'));

        // If the form config is not found then 404
        if (!$field) {
            throw new NotFoundHttpException();
        }

        return view($field->nestedView, [
            'config' => $config,
            'form' => $form,
            'field' => $field,
            'entry' => null,
            'formPath' => [...$request->input('path'), (new DateTime())->format('U-u')],
        ]);
    }

    /**
     * Process an entity action
     */
    public function action(Request $request)
    {
        $action = Crypt::decrypt($request->input('helium_action'));
        $handler = $action['handler'];

        if ($handler) {
            return app()->call($handler, $action['handlerParams']);
        }

        return 404;
    }
}
