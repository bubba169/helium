<?php

namespace Helium\Config\View\Form\Field;

use Helium\Config\Entity;
use Illuminate\Support\Str;
use Helium\Traits\HasConfig;
use Helium\Handler\Field\Value\EntryValueHandler;
use Helium\Handler\Field\Save\DefaultSaveHandler;

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
        $this->scripts = array_normalise_keys($this->scripts, 'src');
        $this->styles = array_normalise_keys($this->styles, 'href');
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
            case 'template':
                return 'helium::form-fields.input';
            case 'autocomplete':
                return 'off';
            case 'attributes':
                return [];
            case 'prepareHandler':
                return null;
            case 'valueHandler':
                return EntryValueHandler::class;
            case 'saveHandler':
                return DefaultSaveHandler::class;
            case 'validationName':
                return Str::lower($this->label);
            case 'scripts':
                return [];
            case 'styles':
                return [];
        }

        return null;
    }

    /**
     * Gets the current value for the field
     *
     * @param mixed The data source to check
     * @return mixed
     */
    public function getExistingValue($source)
    {
        if ($this->valueHandler) {
            return app()->call($this->valueHandler, [
                'source' => $source,
                'field' => $this,
            ]);
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

    /**
     * Gets all scripts required by the field
     */
    public function getScripts() : array
    {
        return $this->scripts;
    }

    /**
     * Gets all styles required by the field
     */
    public function getStyles() : array
    {
        return $this->styles;
    }
}
