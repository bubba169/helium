<?php namespace Helium\Support;

use Helium\Support\Entity;

class EntityTable
{
    /**
     * @var Entity
     */
    protected $entity = null;

    /**
     * Construct
     *
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }
}
