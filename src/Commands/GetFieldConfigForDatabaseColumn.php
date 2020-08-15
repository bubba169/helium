<?php namespace Helium\Commands;

use Helium\FieldTypes\BooleanFieldType;
use Helium\FieldTypes\DateFieldType;
use Helium\FieldTypes\StringFieldType;

class GetFieldConfigForDatabaseColumn
{
    /**
     * @var array
     */
    protected $column;

    /**
     * Constructor
     *
     * @param string $databaseType
     */
    public function __construct(array $column)
    {
        $this->column = $column;
    }

    public function handle()
    {

    }
}
