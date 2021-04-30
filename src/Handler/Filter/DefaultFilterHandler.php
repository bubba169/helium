<?php

namespace Helium\Handler\Filter;

use Helium\Config\Table\Filter\Filter;
use Illuminate\Http\Request;

class DefaultFilterHandler
{
    public function __invoke($query, Filter $filter, Request $request)
    {
        $value = $request->query($filter->name);
        if ($value) {
            $query->where($filter->column, $value);
        }

        return $query;
    }
}
