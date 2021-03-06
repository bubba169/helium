<?php

namespace Helium\Handler\Filter;

use Helium\Config\Table\Filter\Filter;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class RelationshipFilterHandler
{
    public function __invoke($query, Filter $filter, Request $request)
    {
        $value = $request->query($filter->name);
        if ($value) {
            // Relationship type can use has to check for related type
            $query->whereHas(
                $filter->relationship,
                function ($q) use ($value) {
                    $q->whereIn($q->getModel()->getQualifiedKeyName(), Arr::wrap($value));
                }
            );
        }

        return $query;
    }
}
