<?php

namespace Helium\Form;

use Illuminate\Database\Eloquent\Model;

class RelatedOptionsHandler
{
    public function __invoke(array $fieldConfig)
    {
        return $fieldConfig['related_model']::pluck($fieldConfig['related_id'], $fieldConfig['related_name']);
    }
}

