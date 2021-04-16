<?php

namespace Helium\Table;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class DefaultFilterHandler
{
    public function __invoke($query, array $filterConfig, Request $request)
    {
        $value = $request->query($filterConfig['slug']);
        if ($value) {
            switch ($filterConfig['type']) {
                case 'belongsTo':
                    // Relationship type can use has to check for related type
                    $query->whereHas(
                        $filterConfig['relationship'],
                        function ($q) use ($filterConfig, $request) {
                            $q->whereIn(
                                $q->getModel()->getTable() . '.' . $filterConfig['related_id'],
                                Arr::wrap($request->query($filterConfig['slug']))
                            );
                        }
                    );
                    break;
                case 'text':
                case 'textarea':
                    // Text types filter with LIKE
                    $query->where(
                        $filterConfig['column'],
                        'LIKE',
                        '%' . $request->query($filterConfig['slug']) . '%'
                    );
                    break;
                case 'search':
                    // Compare to all of the fields to see if any match
                    $query->where(function ($q) use ($filterConfig, $request) {
                        $term = $request->query($filterConfig['slug']);
                        foreach (Arr::wrap($filterConfig['column']) as $column) {
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
                    break;
                case 'boolean':
                    // Booleans use yes/no in the query to avoid false and empty being confused
                    $query->where($filterConfig['column'], $request->query($filterConfig['slug']) === 'yes');
                    break;
                default:
                    // Others check for exact match
                    $query->where($filterConfig['column'], $request->query($filterConfig['slug']));
            }
        }

        return $query;
    }
}
