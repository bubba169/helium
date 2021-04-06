<?php

namespace Helium\Form;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FormHandler
{
    public function handle(array $config, Request $request)
    {
        $entry = $config['model']::findOrNew($request->input('id'));
        foreach ($config['form']['fields'] as $field) {
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
                    $entry->{$field['column']} = $request->input($field['name'] . '_date') .
                        ' ' . $request->input($field['name'] . '_time');
                    break;
                default:
                    $entry->{$field['column']} = $request->input($field['name']);
            }
        }
        $entry->save();

        // Redirect back to the current url to avoid post on refresh
        return new RedirectResponse($request->url());
    }
}
