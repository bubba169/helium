<?php

namespace Helium\Http\Requests;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class EntityFormRequest extends FormRequest
{
    protected array $entityConfig;

    protected string $formName;

    protected string $entryId;

    protected array $fields;

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
        $this->fields = call_user_func_array(
            'array_merge',
            $this->entityConfig['forms'][$this->formName]['fields']
        );
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_filter(Arr::pluck($this->fields, 'rules', 'slug'));
    }

    public function handle()
    {
        $entry = $this->entityConfig['model']::findOrNew($this->entryId);
        $form = $this->entityConfig['forms'][$this->formName];

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
        });

        // Redirect back to the current url to avoid post on refresh
        return new RedirectResponse(
            route('helium.entity.form', [$this->entityConfig['slug'], $form['slug'], $entry])
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
}
