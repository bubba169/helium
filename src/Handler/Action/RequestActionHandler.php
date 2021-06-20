<?php

namespace Helium\Handler\Action;

use Helium\Config\Entity;

class RequestActionHandler
{
    public function __invoke(string $requestType, string $type, string $form, ?int $entryId = null)
    {
        $entity = new Entity($type);
        $form = $entity->forms[$form];

        $request = app($requestType, compact('entity', 'form', 'entryId'));
        return $request->handle();
    }
}
