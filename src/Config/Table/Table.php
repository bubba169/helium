<?php

namespace Helium\Config\Table;

use Helium\Config\Entity;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Helium\Traits\HasConfig;
use Helium\Config\Table\Action;
use Helium\Config\Table\Filter\Filter;
use Helium\Config\Table\Filter\Search;
use Helium\Table\DefaultListingHandler;

class Table
{
    use HasConfig;

    public ?Search $search;
    public $columns = [];
    public $filters = [];
    public $actions = [];

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
            $this->search = new Search($table['search'], $entity);
        }

        $table['filters'] = array_normalise_keys(Arr::get($table, 'filters', []), 'slug', 'column');
        foreach ($table['filters'] as $filter) {
            $class = Arr::get('field', $filter, Filter::class);
            $this->filters[] = new $class($filter, $entity);
        }

        $table['columns'] = array_normalise_keys(Arr::get($table, 'columns', []), 'slug', 'value');
        foreach ($table['columns'] as $column) {
            $this->columns[] = new Column($column, $entity);
        }

        $table['actions'] = array_normalise_keys(Arr::get($table, 'actions', []), 'slug', 'action');
        foreach ($table['actions'] as $action) {
            $this->actions[] = new Action($action, $entity);
        }
    }

    public function getDefault(string $key)
    {
        switch ($key) {
            case 'title':
                return Str::plural(str_humanise(Str::camel($this->entity->name)));
            case 'query':
                return DefaultListingHandler::class;
            case 'view':
                return 'helium::table';
        }
    }
}
