<?php

namespace Helium\Config\Table;

use Helium\Config\Entity;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Helium\Traits\HasConfig;
use Helium\Config\Table\Filter\Filter;
use Helium\Handler\DefaultListingHandler;
use Helium\Config\Table\Action\TableAction;
use Helium\Config\Table\Filter\SearchFilter;
use Helium\Config\Table\RowAction\RowAction;

class Table
{
    use HasConfig;

    public ?SearchFilter $search;
    public $columns = [];
    public $filters = [];
    public $actions = [];
    public $rowActions = [];

    protected Entity $entity;

    /**
     * Builds a table config
     *
     * @param string|array $config
     */
    public function __construct($table, Entity $entity)
    {
        // If table is a string use it to call a class to get the initial table config
        if (is_string($table)) {
            $table = app()->call($table, ['entity' => $entity]);
        }

        // Set the current config
        $this->entity = $entity;
        $this->mergeConfig($table);

        if (!empty($table['search'])) {
            if (is_string($table['search'])) {
                $this->search = new $table['search'](['slug' => 'search'], $entity);
            } else {
                $class = Arr::get($table['search'], 'base', SearchFilter::class);
                $this->search = new $class($table['search'], $entity);
            }
        }

        $table['filters'] = array_normalise_keys(Arr::get($table, 'filters', []), 'slug', 'base');
        foreach ($table['filters'] as $filter) {
            $class = Arr::get($filter, 'base', Filter::class);
            $this->filters[$filter['slug']] = new $class($filter, $entity);
        }

        $table['columns'] = array_normalise_keys(Arr::get($table, 'columns', []), 'slug', 'base');
        foreach ($table['columns'] as $column) {
            $class = Arr::get($column, 'base', Column::class);
            $this->columns[$column['slug']] = new $class($column, $entity);
        }

        $table['rowActions'] = array_normalise_keys(Arr::get($table, 'rowActions', []), 'slug', 'base');
        foreach ($table['rowActions'] as $action) {
            $class = Arr::get($action, 'base', RowAction::class);
            $this->rowActions[$action['slug']] = new $class($action, $this, $entity);
        }

        $table['actions'] = array_normalise_keys(Arr::get($table, 'actions', []), 'slug', 'base');
        foreach ($table['actions'] as $action) {
            $class = Arr::get($action, 'base', TableAction::class);
            $this->actions[$action['slug']] = new $class($action, $this, $entity);
        }
    }

    public function getDefault(string $key)
    {
        switch ($key) {
            case 'title':
                return Str::plural(str_humanise(Str::camel($this->entity->name)));
            case 'listingHandler':
                return DefaultListingHandler::class;
            case 'view':
                return 'helium::table';
        }
    }
}
