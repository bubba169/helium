<?php namespace Helium\Support;

use Helium\Support\Entity;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class EntityForm
{
    /**
     * @var Entity
     */
    protected $entity = null;

    /**
     * @var Model
     */
    protected $instance = null;

    /**
     * @var Collection
     */
    protected $fields = null;

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
     * Sets the instance to populate the form
     *
     * @param Model $instance
     * @return self
     */
    public function setInstance(?Model $instance) : self
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * Gets the current instance
     *
     * @return Model
     */
    public function getInstance() : Model
    {
        return $this->instance;
    }

    /**
     * Gets the fields
     *
     * @return Collection
     */
    public function getFields() : Collection
    {
        return $this->fields;
    }

    /**
     * Builds a form descriptor for the view
     *
     * @param Model|null $model
     * @return this
     */
    public function build(?Model $instance = null) : self
    {
        $this->fields = $this->buildFields($this->entity->getFields(), $instance);
        return $this;
    }

    /**
     * Builds and populates the fields
     *
     * @param Collection $fields
     * @param Model $instance
     * @return Collection
     */
    protected function buildFields(array $fields) : Collection
    {
        return collect($fields)->mapWithKeys(function ($field, $name) {
            $type = app()->make($field['type'])
                ->setId($field['id'] ?? null)
                ->setName($field['name'] ?? null)
                ->setLabel($field['label'] ?? null)
                ->mergeAttributes($field['attributes'] ?? [])
                ->mergeConfig($field['config'] ?? []);

            if (isset($field['options'])) {
                $type->setOptions($field['options'] ?? []);
            }

            if (isset($field['placeholder'])) {
                $type->setOptions($field['placeholder'] ?? []);
            }

            if ($this->instance) {
                $type->setValue($this->instance->{$name});
            }

            return [$name => $type];
        });
    }
}
