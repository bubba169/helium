<?php namespace Helium\Support;

use Helium\Support\EntityForm;
use Helium\Support\EntityTable;
use Helium\Support\EntityRepository;

class Entity
{
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
     * Constructor
     *
     * @param EntityRepository $repository
     * @param EntityForm $form
     * @param EntityTable $table
     */
    public function __construct(
        EntityRepository $repository,
        EntityForm $form,
        EntityTable $table
    ) {
        $this->repository = $repository;
        $this->form = $form->setEntity($this);
        $this->table = $table->setEntity($this);
    }

    /**
     * Gets the form builder
     *
     * @return EntityForm
     */
    public function getForm() : EntityForm
    {
        return $this->form;
    }

    /**
     * Gets the table builder
     *
     * @return EntityTable
     */
    public function getTable() : EntityTable
    {
        return $this->table;
    }

    /**
     * Gets the model class
     *
     * @return EntityRepository
     */
    public function getRepository() : EntityRepository
    {
        return $this->repository;
    }

    /**
     * Gets the table name
     *
     * @return string
     */
    public function getTableName() : string
    {
        return $this->repository->tableName();
    }
}
