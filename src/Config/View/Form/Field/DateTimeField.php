<?php

namespace Helium\Config\View\Form\Field;

use Helium\Config\Entity;
use Illuminate\Support\Arr;
use Helium\Config\View\Form\Field\Field;
use Helium\Handler\Prepare\DateTimePrepareHandler;

class DateTimeField extends Field
{
    public function __construct(array $field, Entity $entity)
    {
        // Datetime is a special case that splits the attributes between two fields
        // Merge all of the attributes with two special keys for the date and time fields
        $attributes = Arr::get($field, 'attributes', []);
        $common = Arr::except($attributes, ['date', 'time']);
        $field['attributes'] = [
            'date' => array_normalise_keys(array_merge($common, Arr::get($attributes, 'date', []))),
            'time' => array_normalise_keys(array_merge($common, Arr::get($attributes, 'time', []))),
        ];

        if (is_string(Arr::get($field, 'classes'))) {
            $field['classes'] = [
                'date' => $field['classes'],
                'time' => $field['classes']
            ];
        }

        parent::__construct($field, $entity);
    }

    /**
     * {@inheritDoc}
     *
     * Use password type and don't populate value
     */
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'template':
                return 'helium::form-fields.datetime';
            case 'prepareHandler':
                return DateTimePrepareHandler::class;
        }

        return parent::getDefault($key);
    }
}
