<?php namespace Helium\Support;

use Helium\Support\Entity;

class EntityTable
{
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
}
