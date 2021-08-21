<?php

namespace Helium\Config\View\Table;

use Helium\Config\Entity;
use Illuminate\Support\Str;
use Helium\Traits\HasConfig;
use Illuminate\Database\Eloquent\Model;
use Helium\Handler\Column\DefaultColumnViewHandler;
use Helium\Handler\Column\DefaultColumnValueHandler;

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
            case 'valueHandler':
                return DefaultColumnValueHandler::class;
            case 'label':
                return Str::title(str_humanise($this->slug));
            case 'template':
                return 'helium::table-cell.value';
        }

        return null;
    }

    public function getValue(Model $entry): string
    {
        return app()->call($this->valueHandler, [
            'column' => $this,
            'entry' => $entry,
        ]);
    }
}
