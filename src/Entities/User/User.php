<?php namespace Helium\Entities\User;

use Helium\Support\Entity;
use Helium\Entities\User\UserForm;
use Illuminate\Support\Collection;
use Helium\Entities\User\UserTable;
use Helium\FieldTypes\SelectFieldType;
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

    /**
     * {@inheritDoc}
     */
    public function getFields() : Collection
    {
        $fields = parent::getFields();

        $fields = $fields->slice(0, 2)
            ->put('thingy', [
                'name' => 'thingy',
                'type' => SelectFieldType::class,
                'options' => [
                    1 => 'Hello',
                    2 => 'Bob',
                    3 => 'Andy'
                ]
            ])
            ->merge($fields->slice(2));

        return $fields;
    }
}
