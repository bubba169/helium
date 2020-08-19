<?php namespace Helium\FieldTypes;

use Illuminate\Support\Collection;

class SelectFieldType extends FieldType
{
    protected $view = 'helium::input.select';

    /**
     * @var Collection|string
     */
    protected $options;

    public function __construct()
    {
        parent::__construct();
        $this->setPlaceholder(trans('helium::input.select.placeholder'));
    }

    /**
     * Gets the current options
     *
     * @return Collection
     */
    public function getOptions() : Collection
    {
        return $this->options;
    }

    /**
     * Sets the options. They should be in a [value => name] format
     *
     * @param array $options
     * @return self
     */
    public function setOptions(array $options) : self
    {
        $this->options = collect($options);
        return $this;
    }
}
