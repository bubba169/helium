<?php

namespace Helium\Config\Form\Field;

use Helium\Config\Form\Field\Field;
use Helium\Handler\Save\ArraySaveHandler;
use Helium\Handler\Value\EntryArrayValueHandler;

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
            case 'saveHandler':
                return ArraySaveHandler::class;
            case 'valueHandler':
                return EntryArrayValueHandler::class;
        }

        return parent::getDefault($key);
    }
}
