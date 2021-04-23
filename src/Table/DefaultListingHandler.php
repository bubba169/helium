<?php

namespace Helium\Table;

use Helium\Config\Entity;

class DefaultListingHandler
{
    public function __invoke(Entity $entity)
    {
        return $entity->model::query();
    }
}
