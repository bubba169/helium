<?php

namespace Helium\Handler\Action;

use Helium\Config\Entity;

class DeleteActionHandler
{
    public function __invoke(
        string $type,
        string $deleteHandler,
        array $cascade,
        int $entryId,
        string $redirect,
        array $redirectParams
    ) {
        $entity = new Entity($type);

        app()->call(
            $deleteHandler,
            [
                'entry' => $entity->model::findOrFail($entryId),
                'cascade' => $cascade,
            ]
        );

        return redirect(route($redirect, $redirectParams))->with('message', [
            'type' => 'success',
            'message' => $entity->name . ' deleted',
        ]);
    }
}
