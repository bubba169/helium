<?php namespace Helium\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class EntityRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Constructor
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Finds an instance
     *
     * @param string $id
     * @return Model
     */
    public function find(string $id) : Model
    {
        return $this->model->find($id);
    }

    /**
     * Finds all instances
     *
     * @return Collection
     */
    public function all() : Collection
    {
        return $this->model->all();
    }

    /**
     * Gets a query builder
     *
     * @return Builder
     */
    public function query() : Builder
    {
        return $this->model->query();
    }

    /**
     * Gets the model's table name
     *
     * @return string
     */
    public function tableName() : string
    {
        return $this->model->getTable();
    }
}
