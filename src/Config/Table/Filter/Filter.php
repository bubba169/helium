<?php

namespace Helium\Config\Table\Filter;

use Helium\Config\Form\Field\Field;
use Helium\Handler\Filter\DefaultFilterHandler;

class Filter extends Field
{
    /**
     * {@inheritDoc}
     */
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'handler':
                return DefaultFilterHandler::class;
            case 'placeholder':
                return 'Filter By ' . $this->label;
            case 'value':
                return '{request.' . $this->name . '}';
        }

        return parent::getDefault($key);
    }
}
