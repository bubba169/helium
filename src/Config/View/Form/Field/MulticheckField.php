<?php

namespace Helium\Config\View\Form\Field;

use Helium\Config\View\Form\Field\Field;
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
            case 'template':
                return 'helium::form-fields.multicheck';
            case 'saveHandler':
                return ArraySaveHandler::class;
            case 'valueHandler':
                return EntryArrayValueHandler::class;
            case 'options':
                return [];
            case 'optionsHandlerParams':
                return [];
        }

        return parent::getDefault($key);
    }
}
