<?php namespace Helium\Support\Table;

use Helium\Support\Entity;

class TableBuilder
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
