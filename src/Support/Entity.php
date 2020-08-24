<?php namespace Helium\Support;

use Helium\Form\FormBuilder;
use Helium\Database\TableReader;
use Helium\Form\FormHandler;
use Helium\Support\Table\TableBuilder;
use Illuminate\Support\Collection;
use Helium\Support\EntityRepository;

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
}
