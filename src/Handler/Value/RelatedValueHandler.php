<?php

namespace Helium\Handler\Value;

use Illuminate\Support\Str;
use Helium\Config\View\Form\Field\Field;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class RelatedValueHandler
{
    // Fetches the value from the entry
    public function __invoke(?Model $source = null, Field $field) : ?Collection
    {
        if (!$source) {
            return null;
        }

        $relationship = $source->{$field->relationship}();

        if ($field->sequenceColumn) {
            if (Str::startsWith($field->sequenceColumn, 'pivot.')) {
                $pivotColumnName = preg_replace('/^pivot\./', '', $field->sequenceColumn);
                $relationship->withPivot($pivotColumnName)->orderBy('pivot_' . $pivotColumnName);
            } else {
                $relationship->orderBy($field->sequenceColumn);
            }
        }

        return $relationship->get();
    }
}
