<?php

namespace Helium\Form;

class RelatedOptionsHandler
{
    public function handle($entry, $field)
    {
        return $field['related_model']::pluck($field['related_id'], $field['related_name']);
    }
}

