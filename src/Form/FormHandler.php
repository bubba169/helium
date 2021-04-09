<?php

namespace Helium\Form;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Model;

class FormHandler
{
    public function handle(array $config, Request $request)
    {
        $entry = $config['model']::findOrNew($request->input('id'));
        DB::transaction(function () use ($config, $request, $entry) {
            $deferred = [];

            $fields = call_user_func_array(
                'array_merge',
                $config['forms'][$request->input('helium_form')]['fields']
            );

            foreach ($fields as $field) {
                switch ($field['type']) {
                    // Defer some types as they cannot be saved until after the
                    // main entry has been saved
                    case 'belongsToMany':
                        $deferred[] = $field;
                        break;
                    default:
                        $this->handleField($field, $entry, $request);
                }
            }
            $entry->save();

            foreach ($deferred as $field) {
                $this->handleField($field, $entry, $request);
            }
        });

        // Redirect back to the current url to avoid post on refresh
        return new RedirectResponse(route('helium.entity.edit', [$config['slug'], $entry]));
    }

    /**
     * Handles transferring the request data to the model
     */
    protected function handleField(array $field, Model &$entry, Request $request) : void
    {
        switch ($field['type']) {
            case 'belongsToMany':
                $entry->{$field['relationship']}()->sync($request->input($field['name'], []));
                break;
            case 'multicheck':
                $entry->{$field['column']} = json_encode($request->input($field['name'], []));
                break;
            case 'checkbox':
                $entry->{$field['column']} = $request->input($field['name'], false) ? 1 : 0;
                break;
            case 'datetime':
                $entry->{$field['column']} = trim(
                    $request->input($field['name'] . '_date') . ' ' .
                    $request->input($field['name'] . '_time')
                ) ?: null;
                break;
            case 'password':
                // Passwords should only be set if present.
                if (!empty($request->input($field['name']))) {
                    $entry->{$field['column']} = Hash::make($request->input($field['name']));
                }
                break;
            default:
                $entry->{$field['column']} = $request->input($field['name']) ?? null;
        }
    }
}
