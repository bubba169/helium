<?php

namespace Helium\Handler\Filter;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Helium\Config\View\Table\Filter\Filter;

class RelationshipFilterHandler
{
    public function __invoke(Builder $query, Filter $filter, Request $request)
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
