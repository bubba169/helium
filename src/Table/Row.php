<?php namespace Helium\Table;

use Helium\Traits\HasConfig;
use Illuminate\Database\Eloquent\Model;

class Row
{
    use HasConfig;
    /**
     * The instance to use to populate the row
     *
     * @var Model
     */
    protected $intance;

    /**
     * Gets the row model instance
     */
    public function getInstance() : Model
    {
        return $this->instance;
    }

    /**
     * Sets the model instance
     *
     * @param Model $instance
     * @return this
     */
    public function setInstance(Model $instance) : self
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * Gets the actions for the rows
     *
     * @return array
     */
    public function getActions() : array
    {
        return $this->getConfig('actions');
    }
}
