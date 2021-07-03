<?php

namespace Helium\Handler\Value;

use Illuminate\Http\Request;
use Helium\Config\View\Form\Field\Field;

class RequestValueHandler
{
    // Fetches the value from the entry
    public function __invoke(Request $request, Field $field) : ?string
    {
        return $request->input($field->name);
    }
}
