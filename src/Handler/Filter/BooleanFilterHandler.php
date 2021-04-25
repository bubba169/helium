<?php

namespace Helium\Handler\Filter;

use Helium\Config\Table\Filter\Filter;
use Illuminate\Http\Request;

class BooleanFilterHandler
{
    public function __invoke($query, Filter $filter, Request $request)
    {
        $value = $request->query($filter->name);
        if ($value) {
            // Booleans use yes/no in the query to avoid false and empty being confused
            $query->where($filter->column, $value === 'yes');
        }

        return $query;
    }
}
