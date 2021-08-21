<?php

namespace Helium\Handler\Filter;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Helium\Config\View\Table\Filter\Filter;

class BooleanFilterHandler
{
    public function __invoke(Builder $query, Filter $filter, Request $request)
    {
        $value = $request->query($filter->name);
        if ($value) {
            // Booleans use yes/no in the query to avoid false and empty being confused
            $query->where($filter->column, $value === 'yes');
        }

        return $query;
    }
}
