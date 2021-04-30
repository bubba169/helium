<?php

namespace Helium\Config\Form\Field;

use Helium\Config\Form\Field\Field;
use Helium\Handler\Save\ArraySaveHandler;

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
        }

        return parent::getDefault($key);
    }
}
