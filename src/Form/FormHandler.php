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
                case 'checkbox':
                    $entry->{$field['name']} = $request->input($field['name'], false) ? 1 : 0;
                    break;
                case 'datetime':
                    $entry->{$field['name']} = $request->input($field['name'] . '_date') .
                        ' ' . $request->input($field['name'] . '_time');
                    break;
                default:
                    $entry->{$field['name']} = $request->input($field['name']);
            }
        }
        $entry->save();

        // Redirect back to the current url to avoid post on refresh
        return new RedirectResponse($request->url());
    }
}
