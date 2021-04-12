<?php

namespace Helium\Http\Requests;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class SaveEntityFormRequest extends FormRequest
{
    protected array $entityConfig;

    protected string $formName;

    protected ?array $formConfig;

    protected string $entryId;

    protected ?array $fields;

    /**
     * {@inheritDoc}
     */
    public function __construct(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null,
        ?array $entityConfig = null,
        ?string $formName = null,
        ?string $entryId = null
    ){
        $this->entityConfig = $entityConfig;
        $this->formName = $formName;
        $this->entryId = $entryId;
        $this->formConfig = Arr::get($this->entityConfig, 'forms.' . $this->formName);
        $this->fields = call_user_func_array(
            'array_merge',
            $this->entityConfig['forms'][$this->formName]['fields']
        );
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    /**
     * {@inheritDoc}
     */
    public function authorize()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return array_filter(Arr::pluck($this->fields, 'rules', 'slug'));
    }

    /**
     * {@inheritDoc}
     */
    public function messages()
    {
        return Arr::get($this->formConfig, 'messages', []);
    }

    /**
     * {@inheritDoc}
     */
    protected function prepareForValidation()
    {
        foreach ($this->fields as $field) {
            switch ($field['type']) {
                // Put the date and time back together for validation
                case 'datetime':
                    $this->merge([
                        $field['name'] => trim(
                            $this->input($field['name'] . '_date') . ' ' .
                            $this->input($field['name'] . '_time')
                        ) ?: null
                    ]);
                    break;
            }
        }
    }
    /**
     * Handle the request and save the record
     *
     * @return Response|View
     */
    public function handle()
    {
        $entry = $this->entityConfig['model']::findOrNew($this->entryId);

        DB::transaction(function () use ($entry) {
            $deferred = [];

            foreach ($this->fields as $field) {
                switch ($field['type']) {
                    // Defer some types as they cannot be saved until after the
                    // main entry has been saved. THis is often related types
                    // that can only be attached once the record has been created
                    case 'belongsToMany':
                        $deferred[] = $field;
                        break;
                    default:
                        $this->handleField($field, $entry);
                }
            }
            $entry->save();

            foreach ($deferred as $field) {
                $this->handleField($field, $entry);
            }

            if ($entry->isDirty()) {
                $entry->save();
            }
        });

        session()->flash('message', [
            'type' => 'success',
            'message' => 'Saved succesfully'
        ]);

        // Redirect back to the current url to avoid post on refresh
        return new RedirectResponse(
            route('helium.entity.form', [$this->entityConfig['slug'], $this->formConfig['slug'], $entry])
        );
    }

    /**
     * Handles transferring the request data to the model
     */
    protected function handleField(array $field, Model &$entry) : void
    {
        switch ($field['type']) {
            case 'belongsToMany':
                $entry->{$field['relationship']}()->sync($this->input($field['name'], []));
                break;
            case 'multicheck':
                $entry->{$field['column']} = json_encode($this->input($field['name'], []));
                break;
            case 'checkbox':
                $entry->{$field['column']} = $this->input($field['name'], false) ? 1 : 0;
                break;
            case 'datetime':
                $entry->{$field['column']} = trim(
                    $this->input($field['name'] . '_date') . ' ' .
                    $this->input($field['name'] . '_time')
                ) ?: null;
                break;
            case 'password':
                // Passwords should only be set if present.
                if (!empty($this->input($field['name']))) {
                    $entry->{$field['column']} = Hash::make($this->input($field['name']));
                }
                break;
            default:
                $entry->{$field['column']} = $this->input($field['name']) ?? null;
        }
    }

    public function failedValidation($validator)
    {
        session()->flash('message', [
            'type' => 'error',
            'message' => 'There was a problem saving the entry.',
            'errorList' => $validator->errors()->messages(),
        ]);
        return parent::failedValidation($validator);
    }
}
