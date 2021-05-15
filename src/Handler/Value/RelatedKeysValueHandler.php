<?php

namespace Helium\Handler\Value;

use Helium\Config\Form\Field\Field;
use Illuminate\Database\Eloquent\Model;

class RelatedKeysValueHandler
{
    // Fetches the value from the entry
    public function __invoke(?Model $source = null, Field $field) : ?array
    {
        if (!$source) {
            return null;
        }

        $relationship = $source->{$field->relationship}();
        return $relationship
            ->pluck($relationship->getModel()->getQualifiedKeyName())
            ->toArray()
            ?? $field->defaultValue;
    }
}
