<?php

namespace Helium\Config\Form\Field;

use Helium\Config\Form\Field\Field;

class CheckboxField extends Field
{
    /**
     * {@inheritDoc}
     *
     * Use a hidden type by default and hide the label
     */
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'type':
                return 'checkbox';
            case 'view':
                return 'helium::form-fields.checkbox';
        }

        return parent::getDefault($key);
    }
}
