<?php

namespace Helium\Config\Form\Field;


use Helium\Config\Form\Field\BaseField;

class Password extends BaseField
{
    /**
     * The input type is always password
     */
    public $type = 'password';

    /**
     * {@inheritDoc}
     */
    protected function defaultView(): string
    {
        return 'helium::form-fields.input';
    }

    /**
     * {@inheritDoc}
     *
     * Passwords should never populate their value form the stored data
     */
    protected function defaultValue(): string
    {
        return '';
    }
}
