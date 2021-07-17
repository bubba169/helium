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
     * Renders a form using the config
     */
    public function view(string $type, string $view = '*', ?int $id = null) : View
    {
        $entity = new Entity($type);
        $view = $entity->views[$view] ?? null;

        // If the form config is not found then 404
        if (!$view || !$view->viewHandler) {
            throw new NotFoundHttpException();
        }

        // Pass over to the view handler
        return app()->call($view->viewHandler, [
            'id' => $id,
            'view' => $view,
        ]);
    }

    /**
     * Renders a form section for a repeater
     */
    public function section(Request $request) : View
    {
        $entity = new Entity($request->input('entity'));
        $form = $entity->views[$request->input('form')];

        // If the form config is not found then 404
        if (!$form) {
            throw new NotFoundHttpException();
        }

        $field = $form->getField($request->input('field'));

        // If the form config is not found then 404
        if (!$field) {
            throw new NotFoundHttpException();
        }

        return view($field->entryTemplate, [
            'entity' => $entity,
            'view' => $form,
            'field' => $field,
            'entry' => new $entity->model(),
            'formPath' => [...$request->input('path'), (new DateTime())->format('U-u')],
        ]);
    }

    /**
     * Process an entity action
     */
    public function action(Request $request)
    {
        $action = Crypt::decrypt($request->input('helium_action'));
        $handler = $action['handler'] ?? null;

        if (!$handler) {
            throw new NotFoundHttpException();
        }

        return app()->call($handler, $action['handlerParams']);
    }
}
