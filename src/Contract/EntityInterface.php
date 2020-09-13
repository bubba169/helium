<?php namespace Helium\Contract;

use Helium\Form\FormBuilder;
use Helium\Form\FormHandler;
use Helium\Support\EntityRepository;
use Helium\Support\Table\TableBuilder;
use Illuminate\Database\Eloquent\Model;

interface EntityInterface
{
    /**
     * Gets the form builder
     *
     * @return FormBuilder
     */
    public function getFormBuilder() : FormBuilder;

    /**
     * Gets the form handler
     *
     * @return FormHandler
     */
    public function getFormHandler() : FormHandler;

    /**
     * Gets the table builder
     *
     * @return EntityTable
     */
    public function getTableBuilder() : TableBuilder;

    /**
     * Gets the model class
     *
     * @return EntityRepository
     */
    public function getRepository() : EntityRepository;

    /**
     * Gets the field configuration
     *
     * @return array
     */
    public function getFields() : array;

    /**
     * Gets the display field to show in dropdowns etc
     *
     * @return string
     */
    public function getDisplayField() : string;

    /**
     * Gets the entity slug
     *
     * @return string
     */
    public function getSlug() : string;

    /**
     * Gets the route for an entity action in the CMS
     *
     * @param string $action
     * @param array $parameters
     * @return string
     */
    public function getRoute(string $action, array $parameters = []) : string;

    /**
     * Gets the eloquent model for this entity
     *
     * @return Model
     */
    public function getModel() : Model;
}
