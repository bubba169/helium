<?php

namespace Helium\Config\View\Table;

use Helium\Config\Entity;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Helium\Config\View\View;
use Helium\Config\View\Table\Column;
use Helium\Config\Action\ViewLinkAction;
use Helium\Handler\DefaultListingHandler;
use Helium\Config\View\Table\Filter\Filter;
use Helium\Handler\View\ListingViewHandler;
use Helium\Config\View\Table\Filter\SearchFilter;

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

        $this->search = $this->buildSearch();
        $this->filters = $this->buildFilters();
        $this->columns = $this->buildColumns();
        $this->rowActions = $this->buildRowActions();
        $this->actions = $this->buildActions();
    }

    /**
     * Get default values for the config
     */
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'columns':
                return [
                    $this->entity->keyAttribute,
                    $this->entity->displayAttribute
                ];
            case 'actions':
            case 'rowActions':
            case 'filters':
                return [];
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
    protected function buildSearch(): ?SearchFilter
    {
        $config = $this->getConfig('search');

        if (empty($config)) {
            return null;
        }

        if (is_string($config)) {
            return new $config(['slug' => 'search'], $this->entity);
        }

        $class = Arr::get($config, 'base', SearchFilter::class);
        return new $class($config, $this->entity);
    }

    /**
     * Add any extra filter fields for the dropdown
     */
    protected function buildFilters(): array
    {
        $filters = [];
        $config = array_normalise_keys($this->getConfig('filters'), 'slug', 'base');
        foreach ($config as $filter) {
            $class = Arr::get($filter, 'base', Filter::class);
            $filters[$filter['slug']] = new $class($filter, $this->entity);
        }

        return $filters;
    }

    /**
     * Builds the columns for the table
     */
    protected function buildColumns(): array
    {
        $columns = [];
        $config = array_normalise_keys($this->getConfig('columns'), 'slug', 'base');

        foreach ($config as $column) {
            $class = Arr::get($column, 'base', Column::class);
            $columns[$column['slug']] = new $class($column, $this->entity);
        }

        return $columns;
    }

    /**
     * Builds actions to be shown on each row
     */
    protected function buildRowActions(): array
    {
        $actions = [];
        $config = array_normalise_keys($this->getConfig('rowActions'), 'slug', 'base');
        foreach ($config as $action) {
            $class = Arr::get($action, 'base', ViewLinkAction::class);
            $actions[$action['slug']] = new $class($action, $this->entity);
        }

        return $actions;
    }

    /**
     * Builds actions to show at the top of the table
     */
    protected function buildActions(): array
    {
        $actions = [];
        $config = array_normalise_keys($this->getConfig('actions'), 'slug', 'base');
        foreach ($config as $action) {
            $class = Arr::get($action, 'base', ViewLinkAction::class);
            $actions[$action['slug']] = new $class($action, $this->entity);
        }

        return $actions;
    }
}
