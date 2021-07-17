<?php

namespace Helium\Handler\View;

use Exception;
use Helium\Config\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultViewHandler
{
    public function __invoke(View $view, ?string $id = null)
    {
        // Load the entry or an empty model
        $entry = $view->entity->model::findOrNew($id);

        if ($id && !$entry->exists) {
            // If the entry should exist but doesn't then 404
            if (!$entry) {
                throw new NotFoundHttpException();
            }
        }

        if (!$view->template) {
            return new Exception('No template defined for view ' . $view->slug);
        }

        return view($view->template, [
            'view' => $view,
            'entry' => $entry,
        ]);
    }
}
