<?php namespace Helium\FieldTypes;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class FieldType
{
    /**
     * @var string
     */
    protected $id = '';

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $label = '';

    /**
     * @var string|null
     */
    protected $value = null;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var Collection
     */
    protected $attributes;

    /**
     * @var string
     */
    protected $placeholder = null;

    /**
     * @var Collection
     */
    protected $classes;

    /**
     * @var string
     */
    protected $view = 'helium::input.string';

    public function __construct()
    {
        // Create the empty collections
        $this->classes = collect(['form-control']);
        $this->attributes = collect([]);
    }

    /**
     * Gets the current value
     *
     * @return string|null
     */
    public function getValue() : ?string
    {
        return $this->value;
    }

    /**
     * Sets the value
     *
     * @param string|null $value
     * @return this
     */
    public function setValue(?string $value) : self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Get the whole config collection
     *
     * @return array
     */
    public function getConfig() : array
    {
        return $this->config;
    }

    /**
     * Get one item from the config array using dot notation
     *
     * @param string $key The key to the config attribute
     * @return mixed
     */
    public function getConfigAttribute(string $key)
    {
        return Arr::get($this->getConfig(), $key);
    }

    /**
     * Sets the config array
     *
     * @param array $config
     * @return this
     */
    public function setConfig(array $config) : self
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Sets the config array
     *
     * @param array $config
     * @return this
     */
    public function mergeConfig(array $config) : self
    {
        $this->config = array_merge_deep($this->config, $config);
        return $this;
    }

    /**
     * Gets the view for rendering the field
     *
     * @return string
     */
    public function getView() : string
    {
        return $this->view;
    }

    /**
     * Gets the HTML attributes for the field type
     *
     * @return Collection
     */
    public function getAttributes() : Collection
    {
        return $this->attributes;
    }

    /**
     * Gets a single attribute
     *
     * @param string $key The key to the attribute
     * @return mixed
     */
    public function getAttribute(string $key)
    {
        return $this->getAttributes()->get($key, null);
    }

    /**
     * Gets the HTML attributes for the field type
     *
     * @param array $attributes
     * @return this
     */
    public function setAttributes(array $attributes) : self
    {
        $this->attributes = collect($attributes);
        return $this;
    }

    /**
     * Sets a HTML attributes for the field type
     *
     * @param string $key
     * @param string $value
     * @return this
     */
    public function setAttribute(string $key, string $value) : self
    {
        $this->getAttributes()->put($key, $value);
        return $this;
    }

    /**
     * Merges  HTML attributes into the current collection
     *
     * @param array $attributes
     * @return this
     */
    public function mergeAttributes(array $attributes) : self
    {
        $this->attributes = $this->getAttributes()->mergeRecursive(collect($attributes));
        return $this;
    }

    /**
     * Gets the field ID
     *
     * @return string
     */
    public function getId() : string
    {
        return $this->id ?? $this->name;
    }

    /**
     * Gets the field ID
     *
     * @param string|null $id
     * @return this
     */
    public function setId(?string $id) : self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Gets the field name
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name ?? $this->id;
    }

    /**
     * Gets the field name
     *
     * @param string|null $name
     * @return this
     */
    public function setName(?string $name) : self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets the field label
     *
     * @return string
     */
    public function getLabel() : string
    {
        return $this->label ??
            Str::title(str_replace('_', ' ', $this->name)) ??
            Str::title(str_replace('_', ' ', $this->id));
    }

    /**
     * Gets the field label
     *
     * @param string|null $id
     * @return this
     */
    public function setLabel(?string $label) : self
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Gets the list of classes to apply to the control
     *
     * @return Collection
     */
    public function getClasses() : Collection
    {
        return $this->classes;
    }

    /**
     * Adds a class to the list
     *
     * @param string $class
     * @return self
     */
    public function addClass(string $class) : self
    {
        $this->classes->push($class);
        return $this;
    }

    /**
     * Adds a class to the list
     *
     * @param string $class
     * @return self
     */
    public function removeClass(string $class) : self
    {
        $this->classes = $this->classes->filter(function ($existing) use ($class) {
            return $existing != $class;
        });
        return $this;
    }

    /**
     * Gets the current placeholder
     *
     * @return string|null
     */
    public function getPlaceholder() : ?string
    {
        return $this->placeholder;
    }

    /**
     * Set the placeholder
     *
     * @param string|null $placeholder
     * @return this
     */
    public function setPlaceholder(?string $placeholder) : self
    {
        $this->placeholder = $placeholder;
        return $this;
    }
}
