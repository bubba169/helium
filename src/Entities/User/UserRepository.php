<?php namespace Helium\Entities\User;

use Helium\Support\EntityRepository;
use App\User;

class UserRepository extends EntityRepository
{
    /**
     * Constructor
     *
     * @param UserModel $model
     */
    public function __construct(UserEntity $entity, User $model)
    {
        parent::__construct($entity, $model);
    }
}
