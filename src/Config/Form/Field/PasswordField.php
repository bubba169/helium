<?php

namespace Helium\Config\Form\Field;

use Helium\Config\Form\Field\Field;
use Helium\Handler\Save\PasswordSaveHandler;

class PasswordField extends Field
{
    /**
     * {@inheritDoc}
     *
     * Use password type and don't populate value
     */
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'type':
                return 'password';
            case 'valueHandler':
                return null;
            case 'saveHandler':
                return PasswordSaveHandler::class;
        }

        return parent::getDefault($key);
    }
}
