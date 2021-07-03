<?php

namespace Helium\Handler\Save;

use Illuminate\Http\Request;
use Helium\Config\View\Form\Field\Field;
use Illuminate\Database\Eloquent\Model;

class DefaultSaveHandler
{
    /**
     * Saves the request data for the field to an entry
     */
    public function __invoke(Field $field, Request $request, Model $entry, array $path)
    {
        $entry->{$field->column} = $request->input($field->getDataPath($path));
    }
}
