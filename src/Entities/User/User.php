<?php namespace Helium\Entities\User;

use App\User as UserModel;
use Helium\Support\HeliumEntity;
use Helium\Entities\User\UserForm;
use Helium\Entities\User\UserTable;

class User extends HeliumEntity
{
    public function __construct(
        UserForm $form,
        UserTable $table
    ) {
        parent::__construct(UserModel::class, $form, $table);
    }
}
