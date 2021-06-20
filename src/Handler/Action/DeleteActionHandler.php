<?php

namespace Helium\Handler\Action;

use Helium\Config\Entity;
use Symfony\Component\HttpFoundation\Response;

class DeleteActionHandler
{
    public function __invoke(
        string $type,
        string $deleteHandler,
        array $cascade,
        int $entryId
    ) {
        $entity = new Entity($type);

        app()->call(
            $deleteHandler,
            [
                'entry' => $entity->model::findOrFail($entryId),
                'cascade' => $cascade,
            ]
        );

        return $this->onSuccess($entity);
    }

    /**
     * On successful delete
     */
    protected function onSuccess(Entity $entity): Response
    {
        return redirect(
            route('helium.entity.list', ['type'. $entity->slug])
        )->with('message', [
            'type' => 'success',
            'message' => $entity->name . ' deleted',
        ]);
    }
}
