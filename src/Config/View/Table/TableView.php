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
use Illuminate\Database\Eloquent\Model;

class TableView extends View
{
    public ?SearchFilter $search;
    public array $columns = [];
    public array $filters = [];
    public array $actions = [];

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
            case 'with':
                return [];
            case 'title':
                return Str::plural(str_humanise(Str::camel($this->entity->name)));
            case 'listingHandler':
                return DefaultListingHandler::class;
            case 'template':
                return 'helium::table';
            case 'viewHandler':
                return ListingViewHandler::class;
        }

        return parent::getDefault($key);
    }

    /**
     * Builds actions to be shown on each row
     */
    public function getRowActions(Model $entry): array
    {
        $actions = [];
        $config = $this->getConfig('rowActions');

        if ($this->rowActionsHandler) {
            $config = app()->call(
                $this->rowActionsHandler,
                ['view' => $this, 'config' => $config, 'entry' => $entry]
            );
        }

        $config = array_normalise_keys($config, 'slug', 'base');
        foreach ($config as $action) {
            $class = Arr::get($action, 'base', ViewLinkAction::class);
            $actions[$action['slug']] = new $class($action, $this->entity);
        }

        return $actions;
    }

    /**
     * Builds the search filter to show above the table
     */
    protected function buildSearch(): ?SearchFilter
    {
        $config = $this->getConfig('search');

        if ($this->searchHandler) {
            $config = app()->call($this->searchHandler, ['view' => $this, 'config' => $config]);
        }

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
        $config = $this->getConfig('filters');

        if ($this->filtersHandler) {
            $config = app()->call($this->filtersHandler, ['view' => $this, 'config' => $config]);
        }

        $config = array_normalise_keys($config, 'slug', 'base');
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
        $config = $this->getConfig('columns');

        if ($this->columnsHandler) {
            $config = app()->call($this->columnsHandler, ['view' => $this, 'config' => $config]);
        }

        $config = array_normalise_keys($config, 'slug', 'base');
        foreach ($config as $column) {
            $class = Arr::get($column, 'base', Column::class);
            $columns[$column['slug']] = new $class($column, $this->entity);
        }

        return $columns;
    }

    /**
     * Builds actions to show at the top of the table
     */
    protected function buildActions(): array
    {
        $actions = [];
        $config = $this->getConfig('actions');

        if ($this->actionsHandler) {
            $config = app()->call($this->actionsHandler, ['view' => $this, 'config' => $config]);
        }

        $config = array_normalise_keys($config, 'slug', 'base');
        foreach ($config as $action) {
            $class = Arr::get($action, 'base', ViewLinkAction::class);
            $actions[$action['slug']] = new $class($action, $this->entity);
        }

        return $actions;
    }
}
