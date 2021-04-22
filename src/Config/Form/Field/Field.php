<?php

namespace Helium\Config\Form\Field;

use Helium\Config\Entity;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Field
{
    /**
     * @var $slug
     * The system name for the field
     */
    public string $slug;

    /**
     * The original loaded field config
     */
    protected array $fieldConfig;

    /**
     * The current entity config
     */
    protected Entity $config;

    /**
     * Constructor
     */
    public function __construct(array $field, Entity $config)
    {
        $this->fieldConfig = $field;
        $this->entityConfig = $config;

        $this->slug = $field['slug'];
        $this->fieldConfig['attributes'] = array_normalise_keys(Arr::get('attributes', $field, []));
    }

    /**
     * The default field name
     */
    public function getName(): string
    {
        return Arr::get('name', $this->fieldConfig, $this->slug);
    }

    /**
     * The default field name
     */
    public function getType(): string
    {
        return Arr::get('type', $this->fieldConfig, 'text');
    }

    /**
     * The default field name
     */
    public function getId(): string
    {
        return Arr::get('id', $this->fieldConfig, $this->slug);
    }

    /**
     * A default label made from the humanised slug
     */
    public function getLabel(): ?string
    {
        return Arr::get('label', $this->fieldConfig, Str::title(str_humanise($this->slug)));
    }

    /**
     * The default column
     */
    public function getColumn(): string
    {
        return Arr::get('column', $this->fieldConfig, $this->slug);
    }

    /**
     * The default value
     */
    public function getValue(): string
    {
        return Arr::get('value', $this->fieldConfig, '{entry.' . $this->column . '}');
    }

    /**
     * The default required value
     */
    public function getRequired(): bool
    {
        return Arr::get('required', $this->fieldConfig, false);
    }

    /**
     * The default rules.
     * If the field is required then a required rule will be added.
     */
    public function getRules(): array
    {
        if (array_key_exists('rules', $this->fieldConfig)) {
            return $this->fieldConfig['rules'];
        }

        if ($this->getRequired()) {
            return ['required'];
        }

        return [];
    }

    /**
     * The default view to render the input
     */
    public function getView(): string
    {
        return Arr::get('view', $this->fieldConfig, 'helium::form-fields.input');
    }

    /**
     * The default autocomplete setting for this input type
     */
    public function getAutocomplete(): string
    {
        return Arr::get('autocomplete', $this->fieldConfig, 'off');
    }

    /**
     * The default input attributes
     */
    public function getAttributes(): array
    {
        return $this->fieldConfig['attributes'];
    }

    /**
     * The default classes to add to the field
     */
    public function getClasses(): ?string
    {
        return Arr::get('classes', $this->fieldConfig);
    }

    /**
     * The default placeholder for this type of field
     */
    public function getPlaceholder(): ?string
    {
        return Arr::get('placeholder', $this->fieldConfig);
    }

    /**
     * The default description for a form field
     */
    public function getDescription(): ?string
    {
        return Arr::get('description', $this->fieldConfig);
    }

    /**
     * The default description for a form field
     */
    public function getPrefix(): ?string
    {
        return Arr::get('prefix', $this->fieldConfig);
    }

    /**
     * The default description for a form field
     */
    public function getPostfix(): ?string
    {
        return Arr::get('postfix', $this->fieldConfig);
    }
}
