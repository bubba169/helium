<?php namespace Helium\FieldTypes;

class FieldType
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @var array
     */
    protected $config;

    /**
     * Gets the current value
     *
     * @return string
     */
    public function getValue() : string
    {
        return $this->value;
    }

    /**
     * Sets the value
     *
     * @param string $value
     * @return this
     */
    public function setValue($value) : self
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
}
