<?php

namespace Helium\Config\Form\Field;

use Helium\Config\Form\Field\Field;

class RadioField extends Field
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
                return 'radio';
            case 'view':
                return 'helium::form-fields.radios';
        }

        return parent::getDefault($key);
    }
}
