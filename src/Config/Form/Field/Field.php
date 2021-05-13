<?php

namespace Helium\Config\Form\Field;

use Helium\Config\Entity;
use Helium\Handler\Save\DefaultSaveHandler;
use Helium\Traits\HasConfig;
use Illuminate\Support\Str;

class Field
{
    use HasConfig;

    /**
     * The path to validate using the rules for this field
     */
    public string $validationPath;

    /**
     * The path to the field in the entity config
     */
    public array $fieldPath;

    /**
     * The current entity config
     */
    protected Entity $entity;

    /**
     * Constructor
     */
    public function __construct(array $field, Entity $entity)
    {
        $this->mergeConfig($field);
        $this->entity = $entity;
        $this->validationPath = $this->name;
        $this->fieldPath = [$this->slug];
        $this->attributes = array_normalise_keys($this->attributes);
    }

    /**
     * Sensible defaults
     */
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'name':
            case 'id':
            case 'column':
                return $this->slug;
            case 'type':
                return 'text';
            case 'label':
                return Str::title(str_humanise($this->slug));
            case 'value':
                return '{entry.' . $this->column . '}';
            case 'rules':
                return $this->required ? ['required'] : [];
            case 'view':
                return 'helium::form-fields.input';
            case 'autocomplete':
                return 'off';
            case 'attributes':
                return [];
            case 'prepareHandler':
                return null;
            case 'saveHandler':
                return DefaultSaveHandler::class;
            case 'validationName':
                return Str::lower($this->label);
        }

        return null;
    }

    /**
     * Builds the path to the field data in the request
     * using the current path as a prefix
     */
    public function getDataPath(array $path): string
    {
        $path[] = $this->name;
        return implode('.', $path);
    }

    /**
     * Gets all of the validation rules with prefixed paths
     */
    public function getValidationRules(): array
    {
        return [$this->validationPath => $this->rules];
    }

    /**
     * Gets all of the validation messages with prefixed paths
     */
    public function getValidationMessages(): array
    {
        return [$this->validationPath => $this->messages];
    }

    /**
     * Gets all of the validation messages with prefixed paths
     */
    public function getValidationAttributes(): array
    {
        return [$this->validationPath => $this->validationName];
    }
}
