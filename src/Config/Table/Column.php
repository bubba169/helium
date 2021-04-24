<?php

namespace Helium\Config\Table;

use Helium\Config\Entity;
use Illuminate\Support\Str;
use Helium\Traits\HasConfig;

class Column
{
    use HasConfig;

    protected Entity $entity;

    public function __construct(array $column, Entity $entity)
    {
        $this->entity = $entity;
        $this->mergeConfig($column);
    }

    public function getDefault(string $key)
    {
        switch ($key) {
            case 'value':
                return '{entry.' . $this->slug . '}';
            case 'label':
                return Str::title(str_humanise($this->slug));
            case 'view':
                return 'helium::table-cell.text';
        }

        return null;
    }
}
