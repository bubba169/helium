<?php

namespace Helium\Handler;

use Helium\Config\Entity;
use Illuminate\Http\Request;

class DefaultListingHandler
{
    public function __invoke(Entity $entity, Request $request)
    {
        $query = $entity->model::query();
        $sortOptions = $entity->table->sort;

        if (!empty($sortOptions)) {
            if (count($sortOptions) === 1) {
                // Only one options - this will always be applied
                $sort = explode(':', array_key_first($sortOptions));
            } else {
                $sort = explode(':', $request->input('sort', array_key_first($sortOptions)));
            }

            $query->orderBy($sort[0], $sort[1]);
        }

        if (!empty($entity->table->with)) {
            $query->with($entity->table->with);
        }

        return $query;
    }
}
