<?php

namespace Helium\Config\View\Form\Field;

use Helium\Config\View\Form\Field\Field;

class HiddenField extends Field
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
                return 'hidden';
            case 'label':
                return null;
        }

        return parent::getDefault($key);
    }
}
