<?php

namespace Helium\Config\Table;

use Helium\Config\Entity;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Helium\Config\Table\Filter\Filter;
use Helium\Config\Table\Filter\Search;

class Table
{
    public string $title;
    public string $handler;
    public ?Search $search;
    public $filters = [];
    public $actions = [];

    public array $tableConfig;
    public Entity $entityConfig;

    /**
     * Builds a table config
     *
     * @param string|array $config
     */
    public function __construct($table, Entity $config)
    {
        // If table is a string use it to call a class to get the initial table config
        if (is_string($table)) {
            $table = app()->call($table, ['config' => $config]);
        }

        $this->tableConfig = $table;
        $this->entityConfig = $config;

        if (!empty($table['search'])) {
            $table['search']['slug'] = 'search';
            $this->search = new Search($table['search'], $config);
        }

        if (!empty($table['filters'])) {
            $table['filters'] = array_normalise_keys(Arr::get('filters', $table, []), 'slug', 'column');
            foreach ($table['filters'] as $filter) {
                $filterClass = Arr::get('type', $filter, Filter::class);
                $filters[] = new $filterClass($filter, $config);
            }
        }

        //$config = $this->normaliseTableColumns($config);
        //$config['table']['actions'] = $this->normaliseActions($config['table']['actions'], $config, true);
    }

    /**
     * Gets the default title
     */
    protected function defaultTitle(): string
    {
        return Str::plural(str_humanise(Str::camel($this->entityConfig->name)));
    }

    protected function defaultListingHandler(): string
    {
        return DefaultListingHandler::class;
    }

}
