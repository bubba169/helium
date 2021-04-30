<?php

namespace Helium\Handler\Filter;

use Helium\Config\Table\Filter\Filter;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class SearchHandler
{
    public function __invoke($query, Filter $filter, Request $request)
    {
        $value = $request->query($filter->name);

        if ($value) {
            // Compare to all of the fields to see if any match
            $query->where(function ($q) use ($filter, $value) {
                foreach (Arr::wrap($filter->columns) as $column) {
                    if (strpos($column, '.') !== false) {
                        // Extract the relationship path and field name from the column
                        $parts = explode('.', $column);
                        $field = array_pop($parts);
                        $relationship = implode('.', $parts);

                        // Add a where has condition for the related item.
                        $q->orWhereHas($relationship, function ($q) use ($field, $value) {
                            $table = $q->getModel()->getTable();
                            $q->where($table . '.' . $field, 'LIKE', '%' . $value . '%');
                        });
                    } else {
                        // Add the search as a possible match
                        $q->orWhere($column, 'LIKE', '%' . $value . '%');
                    }
                }
            });
        }

        return $query;
    }
}
