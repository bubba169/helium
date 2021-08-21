<?php

namespace Helium\Config\View\Form\Field;

use Helium\Config\View\Form\Field\Field;
use Helium\Handler\Field\Save\BooleanSaveHandler;

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
            case 'template':
                return 'helium::form-fields.checkbox';
            case 'saveHandler':
                return BooleanSaveHandler::class;
            case 'rules':
                return $this->required ? ['accepted'] : [];
        }

        return parent::getDefault($key);
    }
}
