<?php

namespace Helium\Handler\Options;

use Helium\Config\Form\Field\Field;

class RelatedOptionsHandler
{
    public function __invoke(Field $field)
    {
        $results = $field->relatedModel::all();
        $options = [];

        $options = $results->mapWithKeys(
            fn ($result) => [$result->getKey() => str_resolve($field->relatedName, $result)],
        );

        return $options;
    }
}
