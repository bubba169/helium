<?php

namespace Helium\Config\Form\Field;

use Helium\Config\Form\Field\Field;

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
            case 'view':
                return 'helium::form-fields.select';
        }

        return parent::getDefault($key);
    }
}
