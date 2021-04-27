<?php

namespace Helium\Handler\Save;

use Illuminate\Http\Request;
use Helium\Config\Form\Field\Field;
use Illuminate\Database\Eloquent\Model;

class ArraySaveHandler
{
    /**
     * Arrays should be json encoded
     */
    public function __invoke(Field $field, Request $request, Model $entry)
    {
        $entry->{$field->column} = json_encode($request->input($field->name, []));
    }
}
