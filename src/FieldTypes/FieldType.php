<?php namespace Helium\FieldTypes;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class FieldType
{
    /**
     * @var array
     */
    protected $config = [
        'class' => [
            'form-control',
        ]
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Gets the current value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->getConfig('value');
    }

    /**
     * Get the whole config collection
     *
     * @param string $key THe array key using dot notation
     * @return mixed
     */
    public function getConfig(?string $key = null, $default = null)
    {
        if (!empty($key)) {
            return Arr::get($this->config, $key, $default);
        }
        return $this->config;
    }

    /**
     * Sets an element of the array using dot notation
     *
     * @param array $config
     * @return this
     */
    public function setConfig(string $key, $value) : self
    {
        Arr::set($this->config, $key, $value);
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
        return $this->getConfig('view', 'helium::input.string');
    }

    /**
     * Gets the HTML attributes for the field type
     *
     * @return Collection
     */
    public function getAttributes() : array
    {
        return $this->getConfig('attributes', []);
    }

    /**
     * Gets a single attribute
     *
     * @param string $key The key to the attribute
     * @return string|null
     */
    public function getAttribute(string $key, $default = null) : ?string
    {
        return Arr::get($this->getAttributes(), $key, $default);
    }

    /**
     * Gets the field ID
     *
     * @return string
     */
    public function getId() : string
    {
        return $this->getConfig('id') ?? $this->getConfig('name');
    }

    /**
     * Gets the field name
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->getConfig('name');
    }

    /**
     * Gets the field label
     *
     * @return string
     */
    public function getLabel() : string
    {
        return $this->getConfig('label') ??
            Str::title(str_replace('_', ' ', $this->getConfig('name'))) ??
            Str::title(str_replace('_', ' ', $this->getConfig('id')));
    }

    /**
     * Gets the list of classes to apply to the control
     *
     * @return string
     */
    public function getClassList() : string
    {
        return implode(' ', $this->getConfig('class', []));
    }

    /**
     * Gets the current placeholder
     *
     * @return string|null
     */
    public function getPlaceholder() : ?string
    {
        return $this->getConfig('placeholder');
    }
}
