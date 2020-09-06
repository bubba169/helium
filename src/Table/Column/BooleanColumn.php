<?php namespace Helium\Table\Column;

use Helium\Table\Row;
use Helium\Table\Column\Column;

class BooleanColumn extends Column
{
    /**
     * {@inheritDoc}
     */
    public function getValue(Row $row) : string
    {
        return $row->getInstance()->{$this->getName()} ? 'Yes' : 'No';
    }
}
