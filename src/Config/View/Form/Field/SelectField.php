<?php

namespace Helium\Config\View\Form\Field;

use Helium\Config\View\Form\Field\Field;

class SelectField extends Field
{
    /**
     * {@inheritDoc}
     *
     * Use text area view
     */
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'template':
                return 'helium::form-fields.select';
            case 'options':
                return [];
            case 'optionsHandlerParams':
                return [];
        }

        return parent::getDefault($key);
    }
}
