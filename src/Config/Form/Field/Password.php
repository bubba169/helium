<?php

namespace Helium\Config\Form\Field;

use Helium\Config\Form\Field\Field;

class Password extends Field
{
    /**
     * Set to a hidden type field
     */
    public function getType(): string
    {
        return 'password';
    }

    /**
     * {@inheritDoc}
     *
     * Passwords should never populate their value form the stored data
     */
    public function getValue(): string
    {
        return '';
    }
}
