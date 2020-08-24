<?php namespace Helium\Entities\User\Table;

use Helium\Entities\User\UserEntity;
use Helium\Support\Table\TableBuilder;

class UserTableBuilder extends TableBuilder
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
