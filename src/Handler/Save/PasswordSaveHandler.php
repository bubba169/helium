<?php

namespace Helium\Handler\Save;

use Illuminate\Http\Request;
use Helium\Config\Form\Field\Field;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class PasswordSaveHandler
{
    /**
     * Passwords should only be set if not empty
     */
    public function __invoke(Field $field, Request $request, Model $entry, array $path)
    {
        if (!empty($password = $request->input($field->getDataPath($path)))) {
            $entry->{$field->column} = Hash::make($password);
        }
    }
}
