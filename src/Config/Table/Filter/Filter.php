<?php

namespace Helium\Config\Table\Filter;

use Helium\Config\Form\Field\Field;
use Helium\Traits\FilterDefaults;

class Filter extends Field
{
    use FilterDefaults;

    /**
     * Pass to get filter defaults
     *
     * @param string $key
     * @return void
     */
    public function getDefault(string $key)
    {
        return $this->getFilterDefault($key, parent::getDefault($key));
    }
}
