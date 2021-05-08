<?php

namespace Helium\Handler\Save;

use Illuminate\Http\Request;
use Helium\Config\Form\Field\Field;
use Illuminate\Database\Eloquent\Model;

class BooleanSaveHandler
{
    /**
     * Booleans need to transform the request value to a 1 or 0
     */
    public function __invoke(Field $field, Request $request, Model $entry, array $path)
    {
        $entry->{$field->column} = !empty($request->input($field->getDataPath($path), false)) ? 1 : 0;
    }
}
