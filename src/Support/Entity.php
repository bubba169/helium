<?php namespace Helium\Support;

use Illuminate\Support\Str;
use Helium\Form\FormBuilder;
use Helium\Form\FormHandler;
use Helium\Database\TableReader;
use Illuminate\Support\Collection;
use Helium\Support\EntityRepository;
use Helium\Support\Table\TableBuilder;

class Entity
{
    /**
     * @var string
     */
    protected $formBuilderClass = FormBuilder::class;

    /**
     * @var string
     */
    protected $formHandlerClass = FormHandler::class;

    /**
     * @var string
     */
    protected $tableBuilderClass = TableBuilder::class;

    /**
     * @var string
     */
    protected $repositoryClass = EntityRepository::class;

    /**
     * @var FormBuilder
     */
    protected $formBuilder = null;

    /**
     * @var FormHandler
     */
    protected $formHandler = null;

    /**
     * @var TableBuilder
     */
    protected $tableBuilder = null;

    /**
     * @var EntityRepository
     */
    protected $repository = null;

    /**
     * @var Collection
     */
    protected $fields = null;

    /**
     * @var string;
     */
    protected $displayField = null;

    /**
     * Gets the form builder
     *
     * @return FormBuilder
     */
    public function getFormBuilder() : FormBuilder
    {
        return $this->formBuilder ??
            ($this->formBuilder = app()->makeWith($this->formBuilderClass, ['entity' => $this]));
    }

    /**
     * Gets the form handler
     *
     * @return FormHandler
     */
    public function getFormHandler() : FormHandler
    {
        return $this->formHandler ??
            ($this->formHandler = app()->makeWith($this->formHandlerClass, ['entity' => $this]));
    }

    /**
     * Gets the table builder
     *
     * @return EntityTable
     */
    public function getTableBuilder() : TableBuilder
    {
        return $this->tableBuilder ??
            ($this->tableBuilder = app()->makeWith($this->tableBuilderClass, ['entity' => $this]));
    }

    /**
     * Gets the model class
     *
     * @return EntityRepository
     */
    public function getRepository() : EntityRepository
    {
        return $this->repository ??
            ($this->repository = app()->makeWith($this->repositoryClass, ['entity' => $this]));
    }

    /**
     * Gets the table name
     *
     * @return string
     */
    public function getTableName() : string
    {
        return $this->getRepository()->tableName();
    }

    /**
     * Gets the field configuration
     *
     * @return array
     */
    public function getFields() : array
    {
        // If cached, use the cached version
        if ($this->fields) {
            return $this->fields;
        }

        // Otherwise read the fields from the table
        if ($tableName = $this->getTableName()) {
            return $this->fields = app()->make(TableReader::class)
                ->setTable($tableName)
                ->fields();
        }

        return $this->fields = [];
    }

    /**
     * Gets the display field to show in dropdowns etc
     *
     * @return string
     */
    public function getDisplayField() : string
    {
        if ($this->displayField) {
            return $this->displayField;
        }

        $fields = collect($this->getFields());
        return $fields->has('name') ? 'name' : (
            $fields->has('title') ? 'title' : 'id'
        );
    }

    /**
     * Gets the entity slug from the class name
     *
     * @return string
     */
    public function getSlug() : string
    {
        $classParts = explode('\\', get_class($this));
        $classWithoutNamespace = end($classParts);

        return Str::plural(
            Str::camel(
                str_replace('Entity', '', $classWithoutNamespace)
            )
        );
    }

    /**
     * Gets the route for an entity action
     *
     * @param string $action
     * @param array $parameters
     * @return string
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
}
