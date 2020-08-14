<?php namespace Helium\Entities\User;

use Helium\FieldTypes\StringFieldType;
use Helium\Support\EntityForm;
use Illuminate\Support\Collection;

class UserForm extends EntityForm
{
    /**
     * {@inheritDoc}
     */
    /*protected function getFields() : Collection
    {
        return collect([
            'name' => [
                'type' => StringFieldType::class
            ],
            'email' => [
                'type' => StringFieldType::class
            ]
        ]);
    }*/
}
