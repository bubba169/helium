<?php

namespace Helium\Config\Form\Field;

use Helium\Config\Form\Field\Field;

class MulticheckField extends Field
{
    /**
     * {@inheritDoc}
     */
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'view':
                return 'helium::form-fields.multicheck';
        }

        return parent::getDefault($key);
    }
}
