<?php namespace Helium\Entities\User\Table;

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
}
