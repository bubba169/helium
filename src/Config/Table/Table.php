<?php

namespace Helium\Config\Table;

use Helium\Config\Entity;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Helium\Traits\HasConfig;
use Helium\Config\Table\Filter\Filter;
use Helium\Config\Table\Filter\Search;
use Helium\Table\DefaultListingHandler;

class Table
{
    use HasConfig;

    public ?Search $search;
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

        $this->columns = array_normalise_keys($this->columns, 'slug');

        if (!empty($table['search'])) {
            $this->search = new Search($table['search'], $entity);
        }

        if (!empty($table['filters'])) {
            $table['filters'] = array_normalise_keys(Arr::get('filters', $table, []), 'slug', 'column');
            foreach ($table['filters'] as $filter) {
                $filterClass = Arr::get('field', $filter, Filter::class);
                $filters[] = new $filterClass($filter, $this);
            }
        }

        //$config = $this->normaliseTableColumns($config);
        //$config['table']['actions'] = $this->normaliseActions($config['table']['actions'], $config, true);
    }

    public function getDefault(string $key)
    {
        switch ($key) {
            case 'title':
                return Str::plural(str_humanise(Str::camel($this->entityConfig->name)));
            case 'query':
                return DefaultListingHandler::class;
            case 'view':
                return 'helium::table';
        }
    }
}
