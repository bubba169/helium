<?php namespace Helium\Form\Field;

use Helium\Traits\HasConfig;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class Field
{
    use HasConfig;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mergeConfig([
            'class' => [
                'form-control',
            ]
        ]);
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
