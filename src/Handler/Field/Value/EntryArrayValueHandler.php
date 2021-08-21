<?php

namespace Helium\Handler\Field\Value;

use Illuminate\Support\Arr;
use Helium\Config\View\Form\Field\Field;
use Illuminate\Database\Eloquent\Model;

class EntryArrayValueHandler
{
    // Fetches the value from the entry
    public function __invoke(?Model $source = null, Field $field) : ?array
    {
        return Arr::wrap(json_decode($source->{$field->column}, true)) ?? $field->defaultValue;
    }
}
