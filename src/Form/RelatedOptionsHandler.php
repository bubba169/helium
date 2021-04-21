<?php

namespace Helium\Form;

use Illuminate\Database\Eloquent\Model;

class RelatedOptionsHandler
{
    public function __invoke(array $fieldConfig)
    {
        $results = $fieldConfig['related_model']::all();
        $options = [];
        foreach ($results as $result) {
            $options[str_resolve($fieldConfig['related_name'], $result)] =
                str_resolve($fieldConfig['related_id'], $result);
        }

        return $options;
    }
}

