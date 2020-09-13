<?php namespace Helium\Support;

use Illuminate\Support\Collection;
use Helium\Contract\EntityInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EntityRepository
{
    /**
     * @var EntityInterface
     */
    protected $entity;

    /**
     * Constructor
     *
     * @param Model $model
     */
    public function __construct(EntityInterface $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Gets the Entity
     *
     * @return Entity
     */
    public function getEntity() : EntityInterface
    {
        return $this->entity;
    }

    /**
     * Finds an instance
     *
     * @param string $id
     * @return EntityInterface|null
     */
    public function find(string $id) : ?Model
    {
        return $this->entity->getModel()->find($id);
    }

    /**
     * Finds all instances
     *
     * @return Collection
     */
    public function all() : Collection
    {
        return $this->entity->getModel()->all();
    }

    /**
     * Gets a query builder
     *
     * @return Builder
     */
    public function query() : Builder
    {
        return $this->entity->getModel()->query();
    }

    /**
     * Gets the model's table name
     *
     * @return string
     */
    public function tableName() : string
    {
        return $this->entity->getModel()->getTable();
    }

    /**
     * Gets the dropdown options for the model
     *
     * @return array
     */
    public function dropdownOptions() : array
    {
        return $this->entity->getModel()->pluck($this->entity->getDisplayField(), 'id')->toArray();
    }

    /**
     * Gets a paginated list of results
     *
     * @param int $itemsPerPage Number of items to fetch for each page
     * @return LengthAwarePaginator
     */
    public function paginate(int $itemsPerPage) : LengthAwarePaginator
    {
        return $this->entity->getModel()->paginate($itemsPerPage);
    }

    /**
     * Saves the data
     *
     * @param array $data
     * @return bool
     */
    public function save(array $data) : bool
    {
        $model = $this->entity->getModel()->firstOrNew([
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
