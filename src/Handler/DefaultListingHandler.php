<?php

namespace Helium\Handler;

use Helium\Config\View\View;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DefaultListingHandler
{
    /**
     * Invoke this class as a method
     */
    public function __invoke(View $view, Request $request, array $with = []): LengthAwarePaginator
    {
        $query = $view->entity->model::query();
        $query = $this->applySort($view, $request, $query);
        $query = $this->applyFilters($view, $query);
        $query = $this->applyEagerLoading($with, $query);

        return $this->fetchResults($query);
    }

    /**
     * Applies the sort to the query
     */
    protected function applySort(View $view, Request $request, Builder $query): Builder
    {
        $sortOptions = $view->sort;

        if (!empty($sortOptions)) {
            if (count($sortOptions) === 1) {
                // Only one options - this will always be applied
                $sort = explode(':', array_key_first($sortOptions));
            } else {
                $sort = explode(':', $request->input('sort', array_key_first($sortOptions)));
            }

            $query->orderBy($sort[0], $sort[1]);
        }

        return $query;
    }

    /**
     * Applies the search and filters
     */
    protected function applyFilters(View $view, Builder $query): Builder
    {
        if ($search = $view->search) {
            $query = app()->call($search->filterHandler, [
                'query' => $query,
                'filter' => $search
            ]);
        }

        foreach ($view->filters as $filter) {
            $query = app()->call($filter->filterHandler, [
                'query' => $query,
                'filter' => $filter
            ]);
        }

        return $query;
    }

    /**
     * Applies eager loading if defined in the listing config
     */
    protected function applyEagerLoading(array $with, Builder $query): Builder
    {
        if (!empty($with)) {
            $query->with($with);
        }

        return $query;
    }

    /**
     * Paginate the results
     */
    protected function fetchResults(Builder $query): LengthAwarePaginator
    {
        return $query->paginate(50);
    }
}

