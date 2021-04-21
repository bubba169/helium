<?php

namespace Helium\Config\Form\Field;

use Helium\Config\Form\Field\BaseField;

class Hidden extends BaseField
{
    /**
     * Set to a hidden type field
     */
    public string $type = 'hidden';

    /**
     * {@inheritDoc}
     *
     * No label as the field is invisible
     */
    protected function defaultLabel(): ?string
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    protected function defaultView(): string
    {
        return 'helium::form-fields.input';
    }
}
