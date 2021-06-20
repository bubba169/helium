<?php

namespace Helium\Traits;

use Illuminate\Support\Arr;

trait HasFields
{
    public array $fields = [];

    /**
     * Gets a field by its slug. Can be dot notation to find nested fields
     */
    public function getField(array $path)
    {
        $field = Arr::get($this->fields, array_shift($path), null);

        // No more steps return this field
        if (empty($path) || empty($field)) {
            return $field;
        }

        // Otherwise return a sub-field
        return $field->getField($path);
    }
}
