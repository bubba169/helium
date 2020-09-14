<?php namespace Helium\Support;

use Helium\Contract\HeliumEntity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\ForwardsCalls;

class EntityRepository
{
    use ForwardsCalls;

    /**
     * @var HeliumEntity
     */
    protected $entity;

    /**
     * Constructor
     *
     * @param Model $model
     */
    public function __construct(HeliumEntity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Forwards all calls to the model
     *
     * @param string $name
     * @param array $arguments
     * @return void
     */
    public function __call(string $name, array $arguments)
    {
        return $this->forwardCallTo($this->entity->getModel(), $name, $arguments);
    }

    /**
     * Gets the dropdown options for the model
     *
     * @return array
     */
    public function dropdownOptions() : array
    {
        return $this->pluck($this->entity->getDisplayField(), 'id')->toArray();
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
