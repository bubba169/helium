<?php

namespace Helium\Handler\Prepare;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Helium\Config\Form\Field\Field;

class DateTimePrepareHandler
{
    public function __invoke(Field $field, Request $request, array $path)
    {
        $dataPath = $field->getDataPath($path);
        $data = $request->input();
        Arr::set(
            $data,
            $dataPath,
            trim(
                $request->input($dataPath . '_date') . ' ' .
                $request->input($dataPath . '_time')
            ) ?: null
        );
        $request->merge($data);
    }
}
