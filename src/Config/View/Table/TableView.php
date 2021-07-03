<?php

namespace Helium\Config\View\Table;

use Helium\Config\Action\ViewLinkAction;
use Helium\Config\Entity;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Helium\Config\View\Table\Filter\Filter;
use Helium\Handler\DefaultListingHandler;
use Helium\Config\View\Table\Filter\SearchFilter;
use Helium\Config\View\View;
use Helium\Handler\View\ListingViewHandler;

class TableView extends View
{
    public ?SearchFilter $search;
    public array $columns = [];
    public array $filters = [];
    public array $actions = [];
    public array $rowActions = [];

    /**
     * Builds a table config
     *
     * @param string|array $config
     */
    public function __construct($config, Entity $entity)
    {
        // Set the current config
        parent::__construct($config, $entity);

        $this->search = $this->buildSearch($config);
        $this->filters = $this->buildFilters($config);
        $this->columns = $this->buildColumns($config);
        $this->rowActions = $this->buildRowActions($config);
        $this->actions = $this->buildActions($config);
    }

    /**
     * Get default values for the config
     */
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'title':
                return Str::plural(str_humanise(Str::camel($this->entity->name)));
            case 'listingHandler':
                return DefaultListingHandler::class;
            case 'listingHandlerParams':
                return [];
            case 'template':
                return 'helium::table';
            case 'viewHandler':
                return ListingViewHandler::class;
        }

        return parent::getDefault($key);
    }

    /**
     * Builds the search filter to show above the table
     */
    protected function buildSearch(array $config): ?SearchFilter
    {
        if (empty($config['search'])) {
            return null;
        }

        if (is_string($config['search'])) {
            return new $config['search'](['slug' => 'search'], $this->entity);
        }

        $class = Arr::get($config['search'], 'base', SearchFilter::class);
        return new $class($config['search'], $this->entity);
    }

    /**
     * Add any extra filter fields for the dropdown
     */
    protected function buildFilters(array $config): array
    {
        $filters = [];
        $config['filters'] = array_normalise_keys(Arr::get($config, 'filters', []), 'slug', 'base');
        foreach ($config['filters'] as $filter) {
            $class = Arr::get($filter, 'base', Filter::class);
            $filters[$filter['slug']] = new $class($filter, $this->entity);
        }

        return $filters;
    }

    /**
     * Builds the columns for the table
     */
    protected function buildColumns(array $config): array
    {
        $columns = [];
        $config['columns'] = array_normalise_keys(Arr::get($config, 'columns', []), 'slug', 'base');
        foreach ($config['columns'] as $column) {
            $class = Arr::get($column, 'base', Column::class);
            $columns[$column['slug']] = new $class($column, $this->entity);
        }

        return $columns;
    }

    /**
     * Builds actions to be shown on each row
     */
    protected function buildRowActions(array $config): array
    {
        $actions = [];
        $config['rowActions'] = array_normalise_keys(Arr::get($config, 'rowActions', []), 'slug', 'base');
        foreach ($config['rowActions'] as $action) {
            $class = Arr::get($action, 'base', ViewLinkAction::class);
            $actions[$action['slug']] = new $class($action, $this->entity);
        }

        return $actions;
    }

    /**
     * Builds actions to show at the top of the table
     */
    protected function buildActions(array $config): array
    {
        $actions = [];
        $config['actions'] = array_normalise_keys(Arr::get($config, 'actions', []), 'slug', 'base');
        foreach ($config['actions'] as $action) {
            $class = Arr::get($action, 'base', ViewLinkAction::class);
            $actions[$action['slug']] = new $class($action, $this->entity);
        }

        return $actions;
    }
}
