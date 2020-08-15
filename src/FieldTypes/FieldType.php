<?php namespace Helium\FieldTypes;

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
     * @var array
     */
    protected $attributes = [];

    /**
     * @var string
     */
    protected $view = 'helium::input.string';

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
     * Get the qhole config array
     *
     * @return array
     */
    public function getConfig() : array
    {
        return $this->config;
    }

    /**
     * Get one item from the config array
     *
     * @param string $key
     * @return mixed
     */
    public function getConfigAttribute(string $key)
    {
        return $this->getConfig()[$key] ?? null;
    }

    /**
     * Undocumented function
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
     * @return array
     */
    public function getAttributes() : array
    {
        return $this->attributes;
    }

    /**
     * Gets a single attribute
     *
     * @param string $name
     * @return string
     */
    public function getAttribute(string $name) : string
    {
        return $this->attributes[$name];
    }

    /**
     * Gets the HTML attributes for the field type
     *
     * @param array $attributes
     * @return this
     */
    public function setAttributes(array $attributes) : self
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * Gets the field ID
     *
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * Gets the field ID
     *
     * @param string $id
     * @return this
     */
    public function setId(string $id) : self
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
        return $this->name;
    }

    /**
     * Gets the field name
     *
     * @param string $name
     * @return this
     */
    public function setName(string $name) : self
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
        return $this->label;
    }

    /**
     * Gets the field label
     *
     * @param string $id
     * @return this
     */
    public function setLabel(string $label) : self
    {
        $this->label = $label;
        return $this;
    }
}
