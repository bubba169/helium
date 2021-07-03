<?php

namespace Helium\Handler\Filter;

use Helium\Config\View\Table\Filter\Filter;
use Illuminate\Http\Request;

class TextFilterHandler
{
    public function __invoke($query, Filter $filter, Request $request)
    {
        $value = $request->query($filter->name);
        if ($value) {
            // Text types filter with LIKE
            $query->where($filter->column, 'LIKE', '%' . $value . '%');
        }

        return $query;
    }
}
