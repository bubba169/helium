<?php namespace Helium\Support;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
     * @var Entity
     */
    protected $entity;

    /**
     * @var string;
     */
    protected $displayField = null;

    /**
     * Constructor
     *
     * @param Model $model
     */
    public function __construct(Entity $entity, Model $model)
    {
        $this->entity = $entity;
        $this->model = $model;
    }

    /**
     * Gets the Entity
     *
     * @return Entity
     */
    public function getEntity() : Entity
    {
        return $this->entity;
    }

    /**
     * Gets the repository model
     *
     * @return Model
     */
    public function getModel() : Model
    {
        return $this->model;
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

    /**
     * Gets the dropdown options for the model
     *
     * @return array
     */
    public function dropdownOptions() : array
    {
        return $this->model->pluck($this->entity->getDisplayField(), 'id')->toArray();
    }

    /**
     * Gets a paginated list of results
     *
     * @param int $itemsPerPage Number of items to fetch for each page
     * @return LengthAwarePaginator
     */
    public function paginate(int $itemsPerPage) : LengthAwarePaginator
    {
        return $this->model->paginate($itemsPerPage);
    }

    /**
     * Saves the data
     *
     * @param array $data
     * @return bool
     */
    public function save(array $data) : bool
    {
        $model = $this->model->firstOrNew([
            'id' => $data['id']
        ]);

        foreach ($this->entity->getFields() as $field) {
            if (array_key_exists($field['name'], $data)) {
                $value = $data[$field['name']];

                if ($field['type'] == 'boolean') {
                    $value = !empty($value);
                }

                if ($field['type'] == 'multiple') {
                    if (isset($field['relationship'])) {
                        // Handle saving through the relationship instead
                        $model->{$field['relationship']}()->sync($value ?? []);
                        continue;
                    } else {
                        $value = json_encode($value);
                    }
                }

                $model->{$field['name']} = $value;
            }
        }

        $model->save();
        return true;
    }
}
