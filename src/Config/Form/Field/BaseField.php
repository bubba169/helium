<?php

namespace Helium\Config\Form\Field;

use Exception;
use Helium\Config\Entity;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class BaseField
{
    /**
     * @var $slug
     * The system name for the field
     */
    public string $slug;

    /**
     * @var $name
     * The name for the field as submitted from the form
     */
    public string $name;

    /**
     * @var $id
     * The id of the field element
     */
    public string $id;

    /**
     * @var $view
     * The view used to render the field
     */
    public string $view;

    /**
     * @var $column
     * The column used to save the field value when submitting the form
     */
    public ?string $column;

    /**
     * @var $attributes
     * Additional attributes to add to the input element
     */
    public ?array $attributes;

    /**
     * @var $classes
     * Additional classes to add to the input element
     */
    public ?string $classes;

    /**
     * @var rules
     * An array of validation rules to check in the submitted request
     */
    public ?array $rules;

    /**
     * @var $value
     * The value to populate the input.
     * This is a resolved string so can use the {entity.XXX} syntax
     */
    public ?string $value;

    /**
     * @var $placeholder
     * The placeholder to show in the input
     */
    public ?string $placeholder;

    /**
     * @var $label
     * The label for the input
     */
    public ?string $label;

    /**
     * @var $description
     * The description to show for the input
     */
    public ?string $description;

    /**
     * @var $required
     * Shows the field is required and adds a required validation rule
     * if not otherwise set
     */
    public bool $required;

    /**
     * The original loaded field config
     */
    protected array $fieldConfig;

    /**
     * The current entity config
     */
    protected Entity $config;

    /**
     * Field config
     */
    public function __construct(array $field, Entity $config)
    {
        $this->fieldConfig = $field;
        $this->entityConfig = $config;

        $this->slug = $field['slug'];
        $this->name = Arr::get('name', $field, $this->defaultName());
        $this->id = Arr::get('id', $field, $this->defaultId());
        $this->label = Arr::get('label', $field, $this->defaultLabel());
        $this->column = Arr::get('column', $field, $this->defaultColumn());
        $this->value = Arr::get('value', $field, $this->defaultValue());
        $this->required = Arr::get('required', $field, $this->defaultRequired());
        $this->rules = Arr::get('rules', $field, $this->defaultRules());
        $this->view = Arr::get('view', $field, $this->defaultView());
        $this->autocomplete = Arr::get('autocomplete', $field, $this->defaultAutocomplete());
        $this->attributes = array_normalise_keys(Arr::get('attributes', $field, $this->defaultAttributes()));
        $this->classes = Arr::get('classes', $field, $this->defaultClasses());
        $this->placeholder = Arr::get('placeholder', $field, $this->defaultPlaceholder());
        $this->description = Arr::get('description', $field, $this->defaultDescription());
    }

    /**
     * The default field name
     */
    protected function defaultName(): string
    {
        return $this->slug;
    }

    /**
     * The default field name
     */
    protected function defaultId(): string
    {
        return $this->slug;
    }

    /**
     * A default label made from the humanised slug
     */
    protected function defaultLabel(): ?string
    {
        return Str::title(str_humanise($this->slug));
    }

    /**
     * The default column
     */
    protected function defaultColumn(): string
    {
        return $this->slug;
    }

    /**
     * The default value
     */
    protected function defaultValue(): string
    {
        return '{entry.' . $this->column . '}';
    }

    /**
     * The default required value
     */
    protected function defaultRequired(): bool
    {
        return false;
    }

    /**
     * The default rules.
     * If the field is required then a required rule will be added.
     */
    protected function defaultRules(): array
    {
        if ($this->required) {
            return ['required'];
        }

        return [];
    }

    /**
     * The default view to render the input
     */
    protected function defaultView(): string
    {
        throw new Exception('A view must be defined');
    }

    /**
     * The default autocomplete setting for this input type
     */
    protected function defaultAutocomplete(): string
    {
        return 'off';
    }

    /**
     * The default input attributes
     */
    protected function defaultAttributes(): array
    {
        return [];
    }

    /**
     * The default classes to add to the field
     */
    protected function defaultClasses(): ?string
    {
        return null;
    }

    /**
     * The default placeholder for this type of field
     */
    protected function defaultPlaceholder(): ?string
    {
        return null;
    }

     /**
     * The default description for a form field
     */
    protected function defaultDescription(): ?string
    {
        return null;
    }
}
