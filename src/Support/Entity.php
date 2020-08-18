<?php namespace Helium\Support;

use Helium\Support\EntityForm;
use Helium\Support\EntityTable;
use Helium\Database\TableReader;
use Illuminate\Support\Collection;
use Helium\Support\EntityRepository;

class Entity
{
    /**
     * @var string
     */
    protected $formClass = EntityForm::class;

    /**
     * @var string
     */
    protected $tableClass = EntityTable::class;

    /**
     * @var string
     */
    protected $repositoryClass = EntityRepository::class;

    /**
     * @var EntityForm
     */
    protected $form = null;

    /**
     * @var EntityTable
     */
    protected $table = null;

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
     * @return EntityForm
     */
    public function getForm() : EntityForm
    {
        return $this->form ??
            ($this->form = app()->makeWith($this->formClass, ['entity' => $this]));
    }

    /**
     * Gets the table builder
     *
     * @return EntityTable
     */
    public function getTable() : EntityTable
    {
        return $this->table ??
            ($this->table = app()->makeWith($this->tableClass, ['entity' => $this]));
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
