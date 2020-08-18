<?php namespace Helium\Entities\User;

use Helium\Support\EntityForm;
use Helium\Entities\User\UserEntity;

class UserForm extends EntityForm
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
