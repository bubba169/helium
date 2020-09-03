<?php namespace Helium\Traits;

use Illuminate\Support\Arr;

trait HasConfig
{
    /**
     * @var array
     */
    protected $config = [];

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
}
