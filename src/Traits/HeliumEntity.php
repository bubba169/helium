<?php namespace Helium\Traits;

use Illuminate\Support\Arr;
use Helium\Form\FormBuilder;
use Helium\Form\FormHandler;
use Helium\Database\TableReader;
use Helium\Support\EntityRepository;
use Helium\Support\Table\TableBuilder;
use Illuminate\Database\Eloquent\Model;

trait HeliumEntity
{
    /**
     * The cached field configuration
     *
     * @var array
     */
    protected $fields = null;

    /**
     * Set to override the display field
     *
     * @var string;
     */
    protected $displayField = null;

    /**
     * @inheritDoc
     */
    public function getFormBuilder() : FormBuilder
    {
        return app()->makeWith($this->getFormBuilderClass(), ['entity' => $this]);
    }

    /**
     * @inheritDoc
     */
    public function getFormHandler() : FormHandler
    {
        return app()->makeWith($this->getFormHandlerClass(), ['entity' => $this]);
    }

    /**
     * @inheritDoc
     */
    public function getTableBuilder() : TableBuilder
    {
        return app()->makeWith($this->getTableBuilderClass(), ['entity' => $this]);
    }

    /**
     * @inheritDoc
     */
    public function getRepository() : EntityRepository
    {
        return app()->makeWith($this->getrepositoryClass(), ['entity' => $this]);
    }

    /**
     * @inheritDoc
     */
    public function getFields() : array
    {
        // If cached, use the cached version
        if ($this->fields) {
            return $this->fields;
        }

        // Otherwise read the fields from the table
        if ($tableName = $this->getModel()->getTable()) {
            return $this->fields = app()->make(TableReader::class)
                ->setTable($tableName)
                ->fields();
        }

        return $this->fields = [];
    }

    /**
     * @inheritDoc
     */
    public function getDisplayField() : string
    {
        return Arr::get(
            array_first_available($this->getFields(), ['name', 'title', 'id']),
            'name'
        );
    }

    /**
     * @inheritDoc
     */
    public function getSlug() : string
    {
        return Arr::get(
            array_flip(config('helium.entities')),
            get_class($this)
        );
    }

    /**
     * @inheritDoc
     */
    public function getRoute(string $action, array $parameters = []) : string
    {
        return route(
            'entity.' . $action,
            array_merge(
                [
                    'entityType' => $this->getSlug(),
                ],
                $parameters
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function getModel() : Model
    {
        return $this;
    }

    /**
     * Gets form builder class used for this entity
     *
     * @return string
     */
    protected function getFormBuilderClass() : string {
        return FormBuilder::class;
    }

    /**
     * Gets form handler class used for this entity
     *
     * @return string
     */
    protected function getFormHandlerClass() : string {
        return FormHandler::class;
    }

    /**
     * Gets table builder class used for this entity
     *
     * @return string
     */
    protected function getTableBuilderClass() : string {
        return TableBuilder::class;
    }

    /**
     * Gets table builder class used for this entity
     *
     * @return string
     */
    protected function getrepositoryClass() : string {
        return EntityRepository::class;
    }
}
