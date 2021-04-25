<?php

namespace Helium\Config\Form\Field;

use Helium\Config\Form\Field\Field;

class TextAreaField extends Field
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
                return 'helium::form-fields.textarea';
        }

        return parent::getDefault($key);
    }
}
