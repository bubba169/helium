<?php namespace Helium\Database;

use Doctrine\DBAL\Schema\Column;
use Illuminate\Support\Facades\DB;

class TableReader
{
    /**
     * @var string
     */
    protected $table;

    /**
     * Set the table name to read
     *
     * @param string $table
     * @return this
     */
    public function setTable(string $table) : self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Gets fields from the database table
     *
     * @return array
     */
    public function fields() : array
    {
        $schema = DB::getDoctrineSchemaManager();
        $columns = $schema->listTableColumns($this->table);

        return array_map(
            function ($column) {
                return [
                    'name' => $column->getName(),
                    'type' => $column->getType()->getName(),
                    'length' => $column->getLength(),
                    'precision' => $column->getScale(),
                    'unsigned' => $column->getUnsigned(),
                    'config' => [],
                ];
            },
            $columns
        );
    }
}
