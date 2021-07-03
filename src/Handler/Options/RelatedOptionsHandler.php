<?php

namespace Helium\Handler\Options;

use Helium\Config\View\Form\Field\Field;
use Illuminate\Database\Eloquent\Model;

class RelatedOptionsHandler
{
    public function __invoke(Field $field, Model $entry)
    {
        $results = $entry->{$field->relationship}()->getModel()::all();
        $options = [];

        $options = $results->mapWithKeys(
            fn ($result) => [$result->getKey() => str_resolve($field->relatedName, $result)],
        );

        return $options;
    }
}
