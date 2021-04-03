<?php

namespace Helium\Form;

use Illuminate\Http\Request;

class FormHandler
{
    public function handle(array $config, Request $request)
    {
        $entry = $config['model']::findOrNew($request->input('id'));
        foreach ($config['form']['fields'] as $field) {
            $entry->{$field['name']} = $request->input($field['name']);
        }
        $entry->save();

        // Redirect back to the current url to avoid post on refresh
        return redirect($request->url);
    }
}
