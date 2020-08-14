<?php namespace Helium\Entities\User;

use Helium\Support\Entity;
use Helium\Entities\User\UserForm;
use Helium\Entities\User\UserTable;
use Helium\Entities\User\UserRepository;

class User extends Entity
{
    public function __construct(
        UserRepository $repository,
        UserForm $form,
        UserTable $table
    ) {
        parent::__construct($repository, $form, $table);
    }
}
