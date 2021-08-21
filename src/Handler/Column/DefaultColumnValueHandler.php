<?php

namespace Helium\Handler\Column;

use Helium\Config\View\Table\Column;
use Illuminate\Database\Eloquent\Model;

class DefaultColumnValueHandler
{
    public function __invoke(Model $entry, Column $column)
    {
        return str_resolve($column->value, $entry);
    }
}
