<?php namespace Helium\Database;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TableReader
{
    /**
     * @var string
     */
    protected $table;

    /**
     * Constructors
     *
     * @param string $table
     */
    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * Gets fields from the database table
     *
     * @return void
     */
    public function columns() : Collection
    {
        $schema = DB::getDoctrineSchemaManager();
        $allColumns = $schema->listTableColumns($this->table);

        $columns = collect([]);
        foreach ($allColumns as $columnName => $column) {
            $columns->put($columnName, [
                'name' => $column->getName(),
                'type' => $column->getType()->getName(),
                'length' => $column->getLength(),
            ]);
        }

        return $columns;
    }

}
