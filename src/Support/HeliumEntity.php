<?php namespace Helium\Support;

use Helium\Support\EntityForm;
use Helium\Support\EntityTable;

class HeliumEntity
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
     * @var string
     */
    protected $modelClass = null;

    /**
     * COnstructor
     *
     * @param string $modelClass
     * @param EntityForm $form
     * @param EntityTable $table
     */
    public function __construct(
        string $modelClass,
        EntityForm $form,
        EntityTable $table
    ) {
        $this->modelClass = $modelClass;
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
     * @return void
     */
    public function getModelClass() : string
    {
        return $this->modelClass;
    }
}
