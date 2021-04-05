<?php

namespace Helium\Form;

class RelatedOptionsHandler
{
    public function handle($entry, $field)
    {
        return $entry->{$field['relationship']}
            ->pluck($field['related_id'], $field['related_name']);
    }
}

