<?php

namespace Helium\Config\Form\Field;

use Helium\Config\Form\Field\Field;

class PasswordField extends Field
{
    /**
     * {@inheritDoc}
     *
     * Use password type and don't populate value
     */
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'type':
                return 'password';
            case 'value':
                return null;
        }

        return parent::getDefault($key);
    }
}
