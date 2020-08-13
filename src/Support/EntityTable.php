<?php namespace Helium\Support;

use Helium\Support\HeliumEntity;

class EntityTable
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
}
