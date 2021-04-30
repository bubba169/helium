<?php

namespace Helium\Handler\Save;

use Illuminate\Http\Request;
use Helium\Config\Form\Field\Field;
use Illuminate\Database\Eloquent\Model;

class BelongsToManySaveHandler
{
    /**
     * Use Laravel's sync method to save the relationship
     */
    public function __invoke(Field $field, Request $request, Model $entry)
    {
        $entry->{$field->relationship}()->sync($request->input($field->name, []));
    }
}
