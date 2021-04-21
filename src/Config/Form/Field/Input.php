<?php

namespace Helium\Config\Form\Field;

use Helium\Config\Entity;
use Illuminate\Support\Arr;
use Helium\Config\Form\Field\BaseField;

class Input extends BaseField
{
    /**
     * The input type
     */
    public string $type;

    /**
     * A prefix to show before the field. Can contain HTML.
     */
    public ?string $prefix;

    /**
     * Text to show after the field. Can container HTML.
     */
    public ?string $postfix;

    /**
     * A default HTML input
     */
    public function __construct(array $field, Entity $config)
    {
        $this->type = Arr::get('type', $field, $this->defaultType());
        $this->prefix = Arr::get('prefix', $field, $this->defaultPrefix());
        $this->postfix = Arr::get('postfix', $field, $this->defaultPostfix());

        parent::__construct($field, $config);
    }

    /**
     * The default type
     */
    protected function defaultType(): string
    {
        return 'text';
    }

    /**
     * The default type
     */
    protected function defaultPrefix(): ?string
    {
        return null;
    }

    /**
     * The default type
     */
    protected function defaultPostfix(): ?string
    {
        return null;
    }

    /**
     * The default view
     */
    protected function defaultView(): string
    {
        return 'helium::form-fields.input';
    }
}
