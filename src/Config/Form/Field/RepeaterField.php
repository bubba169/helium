<?php

namespace Helium\Config\Form\Field;

use Exception;
use Helium\Config\Action;
use Helium\Config\Entity;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Helium\Config\Form\Field\Field;
use Helium\Handler\Prepare\RepeaterPrepareHandler;
use Helium\Handler\Save\RepeaterSaveHandler;
use Helium\Handler\Value\RelatedValueHandler;
use Helium\Traits\HasFields;

class RepeaterField extends Field
{
    use HasFields;

    public array $fields = [];

    public Action $addButton;

    public function __construct(array $field, Entity $entity)
    {
        parent::__construct($field, $entity);

        // If given a string, try calling it to build an array form a handler
        if (is_string(Arr::get($field, 'fields'))) {
            $field['fields'] = app()->call($field['fields'], ['field' => $field, 'entity' => $entity]);
        }

        // If nothing is given then error as we need fields.
        if (empty($field['fields'])) {
            throw new Exception('Repeater field type ' . $this->slug . ' must have fields defined');
        }

        // Build the add button
        $this->addButton = new Action(
            array_merge(
                $this->addButtonDefaults(),
                Arr::get($field, 'addButton', []),
            ),
            $entity
        );

        // Build the fields
        $field['fields'] = array_normalise_keys($field['fields'], 'slug', 'field');
        foreach ($field['fields'] as $fieldConfig) {
            $fieldClass = Arr::get($fieldConfig, 'field', Field::class);
            $repeaterField = new $fieldClass($fieldConfig, $entity);
            $repeaterField->validationPath = $this->validationPath . '.*.' . $repeaterField->validationPath;
            $repeaterField->fieldPath = [...$this->fieldPath, ...$repeaterField->fieldPath];
            $this->fields[$repeaterField->slug] = $repeaterField;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'relationship':
                return $this->slug;
            case 'saveHandler':
                return RepeaterSaveHandler::class;
            case 'prepareHandler':
                return RepeaterPrepareHandler::class;
            case 'valueHandler':
                return RelatedValueHandler::class;
            case 'view':
                return 'helium::form-fields.repeater';
            case 'nestedView':
                return 'helium::partials.repeater-form';
            case 'value':
                return '{entry.' . $this->relationship . '}';
            case 'minEntries':
                return 0;
            case 'maxEntries':
                return 0;
            case 'entryLabel':
                return Str::title(Str::singular(str_humanise($this->slug)));
        }

        return parent::getDefault($key);
    }

    /**
     * Undocumented function
     */
    public function addButtonDefaults(): array
    {
        return [
            'slug' => 'add',
            'label' => 'Add ' . Str::title(Str::singular($this->name)),
        ];
    }

    /**
     * {@inheritDoc}
     *
     * Build for all of the child fields as well as the main field
     */
    public function getValidationRules(): array
    {
        // Get an array of validation rules
        $rules = [$this->validationPath => $this->rules];

        foreach ($this->fields as $field) {
            if (!empty($field->rules)) {
                $rules = array_merge($rules, $field->getValidationRules());
            }
        }

        return $rules;
    }

    /**
     * Gets all of the validation messages with prefixed paths
     */
    public function getValidationMessages(): array
    {
        // Get an array of validation rules
        $messages = [$this->validationPath => $this->messages];

        foreach ($this->fields as $field) {
            if (!empty($field->messages)) {
                $messages = array_merge($messages, $field->getValidationMessages());
            }
        }

        return $messages;
    }

     /**
     * Gets all of the validation attributes with prefixed paths
     */
    public function getValidationAttributes(): array
    {
        // Get an array of validation rules
        $attributes = [$this->validationPath => $this->validationName];

        foreach ($this->fields as $field) {
            $attributes = array_merge($attributes, $field->getValidationAttributes());
        }

        return $attributes;
    }

    /**
     * Gets all scripts required by the field
     */
    public function getScripts() : array
    {
        return array_filter(
            call_user_func_array(
                'array_merge',
                array_map(fn ($field) => $field->getScripts(), $this->fields)
            )
        );
    }

    /**
     * Gets all styles required by the field
     */
    public function getStyles() : array
    {
        return array_filter(
            call_user_func_array(
                'array_merge',
                array_map(fn ($field) => $field->getStyles(), $this->fields)
            )
        );
    }
}
