<?php namespace Helium\Support;

use Helium\Support\HeliumEntity;
use Illuminate\Support\Collection;

class EntityForm
{
    /**
     * @var HeliumEntity
     */
    protected $entity = null;

    /**
     * Sets the entity
     *
     * @param HeliumEntity $entity
     * @return this
     */
    public function setEntity(HeliumEntity $entity) : self
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * Gets the current entity
     *
     * @return HeliumEntity
     */
    public function getEntity() : HeliumEntity
    {
        return $this->entity;
    }

    /**
     * Builds a form descriptor for the view
     *
     * @param mixed $model
     * @return void
     */
    public function build($instance = null)
    {
        $fields = $this->getFields();
        $fields = $this->buildFields($fields, $instance);
        return $fields;
    }

    /**
     * Gets the field configuration
     *
     * @return Collection
     */
    protected function getFields() : Collection
    {
        return collect([]);
    }

    /**
     * Populates the fields
     */
    protected function buildFields(Collection $fields, $instance = null)
    {
        return $fields->mapWithKeys(function ($field, $name) use ($instance) {
            $type = app()->make($field['type']);

            if ($instance) {
                $type->setValue($instance->{$name});
            }

            return [$name => $type];
        });
    }
}
