<?php namespace Helium\Support;

use Helium\Support\Entity;
use Helium\Database\TableReader;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Helium\Commands\GetFieldConfigForDatabaseField;
use Helium\Commands\GetFieldConfigForDatabaseColumn;

class EntityForm
{
    use DispatchesJobs;

    /**
     * @var Entity
     */
    protected $entity = null;

    /**
     * Sets the entity
     *
     * @param Entity $entity
     * @return this
     */
    public function setEntity(Entity $entity) : self
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * Gets the current entity
     *
     * @return Entity
     */
    public function getEntity() : Entity
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
        $reader = new TableReader($this->entity->getTableName());
        $columns = $reader->columns();

        $fields = $columns->map(function ($column) {
            return $this->dispatchNow(new GetFieldConfigForDatabaseColumn($column));
        });

        return $fields;
    }

    /**
     * Populates the fields
     */
    protected function buildFields(Collection $fields, $instance = null)
    {
        return $fields->mapWithKeys(function ($field, $name) use ($instance) {
            $type = app()->make($field['type']);

            if (!empty($field['config'])) {
                $type->setConfig($field['config']);
            }

            if ($instance) {
                $type->setValue($instance->{$name});
            }

            return [$name => $type];
        });
    }
}
