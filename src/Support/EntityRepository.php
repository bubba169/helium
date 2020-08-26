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
     * Gets the display field
     *
     * @return string
     */
    public function getDisplayField() : string
    {
        if ($this->displayField) {
            return $this->displayField;
        }

        $fields = collect($this->entity->getFields());
        return $fields->has('name') ? 'name' : (
            $fields->has('title') ? 'title' : 'id'
        );
    }

    /**
     * Gets the dropdown options for the model
     *
     * @return array
     */
    public function dropdownOptions() : array
    {
        return $this->model->pluck($this->getDisplayField(), 'id')->toArray();
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
            if (isset($data[$field['name']])) {
                $value = $data[$field['name']];

                if ($field['type'] == 'boolean') {
                    $value = !empty($value);
                }

                if ($field['type'] == 'multiple') {
                    if (isset($field['relationship'])) {
                        // Handle saving through the relationship instead
                        $model->{$field['relationship']}()->sync($value);
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
