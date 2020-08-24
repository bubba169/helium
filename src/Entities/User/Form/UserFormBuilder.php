<?php namespace Helium\Entities\User\Form;

use Helium\Form\FormBuilder;
use Helium\Entities\User\UserEntity;

class UserFormBuilder extends FormBuilder
{
    /**
     * Construct
     *
     * @param UserEntity $entity
     */
    public function __construct(UserEntity $entity)
    {
        parent::__construct($entity);

        $this->skip[] = 'remember_token';
    }
}
