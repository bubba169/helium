<?php

namespace Helium\Config\View\Table\Filter;

use Helium\Config\View\Form\Field\Field;
use Helium\Handler\Field\Value\RequestValueHandler;
use Helium\Handler\Filter\DefaultFilterHandler;

class Filter extends Field
{
    /**
     * {@inheritDoc}
     */
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'filterHandler':
                return DefaultFilterHandler::class;
            case 'valueHandler':
                return RequestValueHandler::class;
            case 'placeholder':
                return 'Filter By ' . $this->label;
        }

        return parent::getDefault($key);
    }
}
