<?php namespace Helium\Support\Table;

use Helium\Table\Table;
use Helium\Support\Entity;
use Illuminate\Support\Arr;
use Helium\Table\Column\Column;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TableBuilder
{
    /**
     * @var Entity
     */
    protected $entity = null;

    /**
     * @var array
     */
    protected $columns = null;

    /**
     * Construct
     *
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Gets the entity for the table builder
     *
     * @return Entity
     */
    public function getEntity() : Entity
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

        return app()->make(Table::class)
            ->setColumns($this->buildColumns($fields))
            ->setRows($this->buildRows($fields));
    }

    /**
     * Builds columns using the
     *
     * @return array
     */
    protected function buildColumns(array $fields) : array
    {
        $columns = $this->columns ?? ['id', $this->entity->getDisplayField()];

        $columns = array_map(
            function ($column) use ($fields) {
                return $this->buildColumn($column, Arr::get($fields, $column['name']));
            },
            array_normalize_keys($columns, 'name')
        );

        return $columns;
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
     * Builds the rows from the repository
     *
     * @return LengthAwarePaginator
     */
    public function buildRows() : LengthAwarePaginator
    {
        return $this->entity->getRepository()->paginate(20);
    }
}
