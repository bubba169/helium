<?php

namespace Helium\Handler\Save;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Helium\Config\Form\Field\Field;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RepeaterSaveHandler
{
    public static bool $deferred = true;

    /**
     * Saves all the nested fields. Requires all injected parameters so
     * they can be passed on.
     */
    public function __invoke(Field $field, Request $request, Model $entry, array $path): void
    {
        $relatedKey = $entry->{$field->relationship}()->getModel()->getQualifiedKeyName();
        $requestData = $request->input($field->getDataPath($path), []);
        $ids = array_filter(Arr::pluck($requestData, 'id'));

        foreach ($requestData as $i => $data) {
            // Find the related object or create a new one if it doesn't exist
            $related = $entry->{$field->relationship}()->firstOrNew([
                $relatedKey => $data['helium_id']
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

            if ($field->sequenceColumn && $entry->{$field->relationship}() instanceof HasMany) {
                $related->{$field->sequenceColumn} = $data['helium_sequence'];
            }

            $related->save();
            $ids[] = $related->id;

            // For belongs to many we need to attach a pivot record
            if ($entry->{$field->relationship}() instanceof BelongsToMany) {
                $pivotData = [];
                if ($field->sequenceColumn && Str::startsWith($field->sequenceColumn, 'pivot.')) {
                    $pivotColumnName = preg_replace('/^pivot\./', '', $field->sequenceColumn);
                    $pivotData[$pivotColumnName] = $data['helium_sequence'];
                }

                // If recently created we can pass the pivot data to the attach function
                if ($related->wasRecentlyCreated) {
                    $entry->{$field->relationship}()->attach($related, $pivotData);
                // Otherwise we update the existing record directly
                } else {
                    $related->pivot->update($pivotData);
                }
            }
        }

        // Remove any related items no longer in the list
        $entry->{$field->relationship}()->whereNotIn($relatedKey, $ids)->delete();
    }
}
