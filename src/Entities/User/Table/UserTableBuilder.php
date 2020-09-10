<?php namespace Helium\Entities\User\Table;

use Helium\Entities\User\UserEntity;
use Helium\Support\Table\TableBuilder;

class UserTableBuilder extends TableBuilder
{
    protected $columns = [
        'id',
        'name',
        'is_active'
    ];

    protected $actions = [
        'edit' => [
            'icon' => 'far fa-edit',
        ],
    ];

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
