<?php

namespace Helium\Config\View\Form\Field;

use Helium\Config\View\Form\Field\Field;

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
            case 'template':
                return 'helium::form-fields.textarea';
        }

        return parent::getDefault($key);
    }
}
