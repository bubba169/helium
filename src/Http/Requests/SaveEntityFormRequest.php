<?php

namespace Helium\Http\Requests;

use Helium\Config\Entity;
use Helium\Config\View\Form\FormView;
use Illuminate\Support\Facades\DB;
use Helium\Config\View\Form\Field\Field;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class SaveEntityFormRequest extends FormRequest
{
    protected Entity $entity;

    protected FormView $form;

    protected ?string $entryId;

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
        ?Entity $entity = null,
        ?FormView $form = null,
        ?string $entryId = null
    ) {
        $this->entity = $entity;
        $this->form = $form;
        $this->entryId = $entryId;
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
        return $this->form->validationRules();
    }

    /**
     * {@inheritDoc}
     */
    public function messages()
    {
        return $this->form->validationMessages();
    }

    /**
     * {@inheritDoc}
     */
    public function attributes()
    {
        return $this->form->validationAttributes();
    }

    /**
     * {@inheritDoc}
     */
    protected function prepareForValidation()
    {
        foreach ($this->form->allFields() as $field) {
            if (!empty($field->prepareHandler)) {
                app()->call($field->prepareHandler, [
                    'entity' => $this->entity,
                    'field' => $field,
                    'request' => $this,
                    'path' => []
                ]);
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
        $entry = $this->entity->model::findOrNew($this->entryId);

        DB::transaction(function () use ($entry) {
            $deferred = [];

            foreach ($this->form->allFields() as $field) {
                if (!empty($field->saveHandler::$deferred)) {
                    // Defer some types as they cannot be saved until after the
                    // main entry has been saved. THis is often related types
                    // that can only be attached once the record has been created
                    $deferred[] = $field;
                } else {
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
            route('helium.entity.view', [$this->entity->slug, $this->form->slug, $entry])
        );
    }

    /**
     * Handles transferring the request data to the model
     */
    protected function handleField(Field $field, Model &$entry) : void
    {
        if (!empty($field->saveHandler)) {
            app()->call($field->saveHandler, [
                'field' => $field,
                'entry' => $entry,
                'request' => $this,
                'path' => []
            ]);
        }
    }

    /**
     * {@inheritDoc}
     *
     * Add a validation message
     */
    public function failedValidation(Validator $validator): void
    {
        session()->flash('message', [
            'type' => 'error',
            'message' => 'There was a problem saving the entry.',
            'errorList' => $validator->errors()->messages(),
        ]);

        parent::failedValidation($validator);
    }
}
