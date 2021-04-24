<?php namespace Helium\Traits;

use Illuminate\Support\Arr;

trait HasConfig
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * Allows getting attributes from the config array
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->getConfig($name);
    }

    /**
     * Allows getting attributes from the config array
     *
     * @return mixed
     */
    public function __set(string $name, $value)
    {
        $this->setConfig($name, $value);
        return $value;
    }

    /**
     * Override isset to check if a config has been set or has a default
     *
     * @param string $name
     * @return boolean
     */
    public function __isset(string $name)
    {
        return !empty($this->getConfig($name));
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
            return Arr::get($this->config, $key, $default ?? $this->getDefault($key));
        }

        return $this->config;
    }

    /**
     * Gets a default value for a config key
     *
     * @return mixed
     */
    public function getDefault(string $key)
    {
        return null;
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
}
