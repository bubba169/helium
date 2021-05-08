<?php

namespace Helium\Handler\Save;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Helium\Config\Form\Field\Field;
use Illuminate\Database\Eloquent\Model;

class RepeaterSaveHandler
{
    public static bool $deferred = true;

    /**
     * Saves all the nested fields. Requires all injected parameters so
     * they can be passed on.
     */
    public function __invoke(Field $field, Request $request, Model $entry, array $path): void
    {
        $relationship = $entry->{$field->relationship}();
        $relatedKey = $relationship->getModel()->getQualifiedKeyName();

        foreach ($request->input($field->getDataPath($path)) as $i => $data) {
            // Find the related object or create a new one if it doesn't exist
            $related = $relationship->firstOrNew([
                $relatedKey => $data['id']
            ]);

            // Loop through each of the fields in the config and save them using their save handler
            foreach ($field->fields as $relatedField) {
                app()->call($relatedField->saveHandler, [
                    'field' => $relatedField,
                    'entry' => $related,
                    'request' => $request,
                    'path' => [...$path, $field->name, $i]
                ]);
            }

            $related->save();
        }

        // Remove any related items no longer in the list
        $ids = Arr::pluck($request->input($field->getDataPath($path)), 'id');
        $relationship->whereNotIn($relatedKey, $ids)->delete();
    }
}
