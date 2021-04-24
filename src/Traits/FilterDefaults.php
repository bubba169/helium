<?php

namespace Helium\Traits;

use Illuminate\Support\Str;

trait FilterDefaults
{
    public function getFilterDefault(string $key, $default = null)
    {
        switch ($key) {
            case 'filterHandler':
                return DefaultFilterHandler::class;
            case 'placeholder':
                return 'Filter By ' . $this->label;
            case 'value':
                return '{request.' . $this->name . '}';
        }

        return $default;
    }
}
