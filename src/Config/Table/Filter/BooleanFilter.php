<?php

namespace Helium\Config\Table\Filter;

use Helium\Config\Table\Filter\Filter;
use Helium\Handler\Filter\BooleanFilterHandler;

class BooleanFilter extends Filter
{
    /**
     * {@inheritDoc}
     */
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'handler':
                return BooleanFilterHandler::class;
            case 'options':
                return [
                    'Yes' => 'yes',
                    'No' => 'no'
                ];
            case 'view':
                return 'helium::form-fields.select';
        }

        return parent::getDefault($key);
    }
}
