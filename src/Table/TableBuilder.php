<?php namespace Helium\Support\Table;

use Helium\Table\Row;
use Helium\Table\Table;
use Illuminate\Support\Arr;
use Helium\Table\Column\Column;
use Helium\Contract\HeliumEntity;
use Helium\Contract\EntityInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TableBuilder
{
    /**
     * The entity type represented by the table
     *
     * @var HeliumEntity
     */
    protected $entity = null;

    /**
     * Defines any columns to be shown in the table. Columns can be
     * added as a string matching a field name or as an array with
     * a column configuration
     *
     * @var array
     */
    protected $columns = null;

    /**
     * An array of actions
     *
     * @var array
     */
    protected $actions = null;

    /**
     * Construct
     *
     * @param HeliumEntity $entity
     */
    public function __construct(HeliumEntity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Gets the entity for the table builder
     *
     * @return Entity
     */
    public function getEntity() : EntityInterface
    {
        return $this->entity;
    }

    /**
     * Builds a table
     *
     * @return Table
     */
    public function getTable() : Table
    {
        $fields = $this->entity->getFields();
        $paginatedItems = $this->fetchItems();

        return app()->make(Table::class)
            ->setColumns($this->buildColumns($fields))
            ->setRows($this->buildRows($paginatedItems->items()))
            ->setPaginator($paginatedItems);
    }

    /**
     * Builds columns using the
     *
     * @return array
     */
    protected function buildColumns(array $fields) : array
    {
        $columns = $this->columns ?? ['id', $this->entity->getDisplayField()];

        return array_map(
            function ($column) use ($fields) {
                return $this->buildColumn($column, Arr::get($fields, $column['name']));
            },
            array_normalize_keys($columns, 'name')
        );
    }

    /**
     * Builds a column from the config
     *
     * @param array $column
     * @param array|null $field
     * @return array
     */
    protected function buildColumn(array $column, ?array $field) : Column
    {
        return app()->make($this->getColumnType($column, $field))
            ->mergeConfig($column);
    }

    /**
     * Gets the column type based on the field
     *
     * @param array $column
     * @param array|null $field
     * @return string
     */
    protected function getColumnType(array $column, ?array $field) : string
    {
        if ($field) {
            return config(
                'helium.column_types.' . Arr::get($field, 'type'),
                Column::class
            );
        }

        return Column::class;
    }

    /**
     * Builds the rows from the listing items
     *
     * @return LengthAwarePaginator
     */
    protected function buildRows(array $items) : array
    {
        return array_map(
            function ($item) {
                return $this->buildRow($item);
            },
            $items
        );
    }

    protected function buildRow(Model $item) : Row
    {
        return app()->make(Row::class)
            ->setInstance($item)
            ->mergeConfig([
                'actions' => $this->buildActions($item)
            ]);
    }

    /**
     * Gets the listing items
     *
     * @return LengthAwarePaginator
     */
    protected function fetchItems() : LengthAwarePaginator
    {
        return $this->entity->getRepository()->paginate(20);
    }

    /**
     * Builds the actions from the action config
     *
     * @param array $fields
     * @return array
     */
    protected function buildActions(Model $item) : array
    {
        $actions = $this->actions ?? ['edit'];

        return array_map(
            function ($action) use ($item) {
                return $this->buildAction($action, $item);
            },
            array_normalize_keys($actions, 'name')
        );
    }

    /**
     * Builds a single action setting defaults for missing attributes
     *
     * @param array $action
     * @return array
     */
    protected function buildAction(array $action, Model $item) : array
    {
        $action['url'] = $this->entity->getRoute($action['name'], ['id' => $item->id]);
        if (!array_key_exists('label', $action)) {
            $action['label'] = str_humanize($action['name']);
        }
        if (!array_key_exists('title', $action)) {
            $action['title'] = str_humanize($action['name']);
        }
        return $action;
    }


}
