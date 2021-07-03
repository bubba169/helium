<?php

namespace Helium\Config\View\Form\Field;

use Helium\Config\View\Form\Field\Field;

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
            case 'template':
                return 'helium::form-fields.radios';
            case 'options':
                return [];
            case 'optionsHandlerParams':
                return [];
        }

        return parent::getDefault($key);
    }
}
