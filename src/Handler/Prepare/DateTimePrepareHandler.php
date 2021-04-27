<?php

namespace Helium\Handler\Prepare;

use Illuminate\Http\Request;
use Helium\Config\Form\Field\Field;

class DateTimePrepareHandler
{
    public function __invoke(Field $field, Request $request)
    {
        $request->merge([
            $field->name => trim(
                $request->input($field->name . '_date') . ' ' .
                $request->input($field->name . '_time')
            ) ?: null
        ]);
    }
}
