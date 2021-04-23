<?php

namespace Helium\Table;

use Helium\Config\Table\Filter\Search;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class DefaultSearchHandler
{
    public function __invoke($query, Search $search, Request $request)
    {
        $value = $request->query('search');

        if ($value) {
            // Compare to all of the fields to see if any match
            $query->where(function ($q) use ($search, $request) {
                $term = $request->query('search');
                foreach (Arr::wrap($search->columns) as $column) {
                    if (strpos($column, '.') !== false) {
                        // Extract the relationship path and field name from the column
                        $parts = explode('.', $column);
                        $field = array_pop($parts);
                        $relationship = implode('.', $parts);

                        // Add a where has condition for the related item.
                        $q->orWhereHas($relationship, function ($q) use ($field, $term) {
                            $table = $q->getModel()->getTable();
                            $q->where($table . '.' . $field, 'LIKE', '%' . $term . '%');
                        });
                    } else {
                        // Add the search as a possible match
                        $q->orWhere($column, 'LIKE', '%' . $term . '%');
                    }
                }
            });
        }

        return $query;
    }
}
