<?php

namespace Helium\Handler\View;

use Helium\Config\Entity;
use \Helium\Config\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultViewHandler
{
    public function __invoke(Entity $entity, View $view, ?string $id = null)
    {
        // Load the entry or an empty model
        $entry = $entity->model::findOrNew($id);

        if ($id && !$entry->exists) {
            // If the entry should exist but doesn't then 404
            if (!$entry) {
                throw new NotFoundHttpException();
            }
        }

        return view($view->template, [
            'entity' => $entity,
            'view' => $view,
            'entry' => $entry,
        ]);
    }
}
