<?php

namespace Helium\Config\Table\Filter;

use Illuminate\Support\Str;
use Helium\Config\Form\Field\Field;
use Helium\Table\DefaultFilterHandler;

class Filter extends Field
{
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'filterHandler':
                return DefaultFilterHandler::class;
            case 'placeholder':
                return 'Filter By ' . Str::title(str_humanise($this->slug));
        }

        return parent::getDefault($key);
    }
}
