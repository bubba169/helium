<?php namespace Helium\Entities\User;

use Helium\Support\EntityRepository;
use App\User as UserModel;

class UserRepository extends EntityRepository
{
    /**
     * Constructor
     *
     * @param UserModel $model
     */
    public function __construct(UserModel $model)
    {
        parent::__construct($model);
    }
}
