<?php

namespace Helium\Handler;

use Helium\Config\Entity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DefaultListingHandler
{
    /**
     * Invoke this class as a method
     */
    public function __invoke(Entity $entity, Request $request): LengthAwarePaginator
    {
        $query = $entity->model::query();
        $query = $this->applySort($entity, $request, $query);
        $query = $this->applyFilters($entity, $query);
        $query = $this->applyEagerLoading($entity, $query);

        return $this->fetchResults($query);
    }

    /**
     * Applies the sort to the query
     */
    protected function applySort(Entity $entity, Request $request, Builder $query): Builder
    {
        $sortOptions = $entity->table->sort;

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
    protected function applyFilters(Entity $entity, Builder $query): Builder
    {
        if ($search = $entity->table->search) {
            $query = app()->call($search->filterHandler, [
                'query' => $query,
                'filter' => $search
            ]);
        }

        foreach ($entity->table->filters as $filter) {
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
    protected function applyEagerLoading(Entity $entity, Builder $query): Builder
    {
        if (!empty($entity->table->with)) {
            $query->with($entity->table->with);
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

