<?php namespace Helium\Entities\User;

use Helium\Support\EntityTable;
use Helium\Entities\User\UserEntity;

class UserTable extends EntityTable
{
    /**
     * Construct
     *
     * @param UserEntity $entity
     */
    public function __construct(UserEntity $entity)
    {
        parent::__construct($entity);
    }
}
