<?php

namespace Helium\Handler\Field\Save;

use Illuminate\Http\Request;
use Helium\Config\View\Form\Field\Field;
use Illuminate\Database\Eloquent\Model;

class BelongsToManySaveHandler
{
    public static bool $deferred = true;

    /**
     * Use Laravel's sync method to save the relationship
     */
    public function __invoke(Field $field, Request $request, Model $entry, array $path)
    {
        $entry->{$field->relationship}()->sync($request->input($field->getDataPath($path), []));
    }
}
