<?php

namespace Helium\Handler\Action;

use Helium\Config\Entity;

class RequestActionHandler
{
    public function __invoke(
        string $requestType,
        string $type,
        string $form,
        string $redirectUrl,
        ?int $entryId = null
    ) {
        $entity = new Entity($type);
        $form = $entity->views[$form];

        $request = app($requestType, compact('entity', 'form', 'entryId', 'redirectUrl'));
        return $request->handle();
    }
}
