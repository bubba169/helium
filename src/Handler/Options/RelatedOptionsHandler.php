<?php

namespace Helium\Handler\Options;

use Helium\Config\Form\Field\Field;

class RelatedOptionsHandler
{
    public function __invoke(Field $field)
    {
        $results = $field->related_model::all();
        $options = [];

        foreach ($results as $result) {
            $options[str_resolve($field->related_id, $result)] = str_resolve($field->related_name, $result);
        }

        return $options;
    }
}
