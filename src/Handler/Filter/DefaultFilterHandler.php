<?php

namespace Helium\Handler\Filter;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Helium\Config\View\Table\Filter\Filter;

class DefaultFilterHandler
{
    public function __invoke(Builder $query, Filter $filter, Request $request)
    {
        $value = $request->query($filter->name);
        if ($value) {
            $query->where($filter->column, $value);
        }

        return $query;
    }
}
