<?php namespace Helium\Database;

use Illuminate\Support\Str;
use Doctrine\DBAL\Schema\Column;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Helium\FieldTypes\StringFieldType;
use Helium\FieldTypes\ReadOnlyTextFieldType;

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
                    'type' => $this->getFieldTypeForColumn($column),
                    'attributes' => $this->getFieldAttributesForColumn($column),
                    'rules' => $this->getRulesForColumn($column),
                ];
            },
            $columns
        );
    }

    /**
     * Uses sensible defaults to build field configuration based on the database settings
     *
     * @return string
     */
    protected function getFieldTypeForColumn(Column $column) : string
    {
        if (in_array($column->getName(), ['updated_at', 'created_at', 'deleted_at'])) {
            return ReadOnlyTextFieldType::class;
        }

        return config(
            'helium.database.type_map.' . $column->getType()->getName(),
            StringFieldType::class
        );
    }

    /**
     * Builds a default column config from the database settings
     *
     * @param Column $column
     * @return array
     */
    protected function getFieldAttributesForColumn(Column $column) : array
    {
        $attributes = [];
        $columnName = $column->getName();
        $columnType = $column->getType()->getName();

        // Return early - no attributes for these
        if (in_array($columnName, ['created_at', 'updated_at', 'deleted_at'])) {
            return $attributes;
        }

        // If the field is a string set its max length
        if (in_array($columnType, ['string', 'text'])) {
            $attributes['maxlength'] = $column->getLength();
        }

        // The id field should be hidden by default as this
        // shouldn't be changed on a form
        if ($columnName === 'id') {
            $attributes['type'] = 'hidden';
        // Booleans will display as checkboxes by default
        } elseif ($columnType === 'boolean') {
            $attributes['type'] = 'checkbox';
        // If the field name contains "email" assume it's an email type
        } elseif ($columnName === 'email') {
            $attributes['type'] = 'email';
            $attributes['inputmode'] = 'email';
        // If the field name contains "phone" assume it's a phone number
        } elseif ($columnName === 'phone') {
            $attributes['type'] = 'tel';
            $attributes['inputmode'] = 'tel';
        // If the field name contains "password" assume it's a password
        } elseif ($columnName === 'password') {
            $attributes['type'] = 'password';
        // If the field name contains "password" assume it's a password
        } elseif (strpos('url', $columnName) !== false) {
            $attributes['inputmode'] = 'url';
        // For numeric types
        } elseif (in_array($columnType, ['integer', 'bigint', 'smallint', 'decimal', 'float'])) {
            $attributes['inputmode'] = 'numeric';
            $attributes['type'] = 'number';
            if ($column->getUnsigned()) {
                $attributes['min'] = 0;
            }
            if ($column->getScale() > 0) {
                $attributes['step'] = pow(10, -$column->getScale());
            }
        // For everything else
        } else {
            $attributes['type'] = 'text';
        }

        return $attributes;
    }

    /**
     * Gets some default validation rules based on the database settings
     *
     * @param Column $column
     * @return array
     */
    protected function getRulesForColumn(Column $column) : array
    {
        $rules = [];
        $columnType = $column->getType()->getName();
        if (in_array($columnType, ['string', 'text'])) {
            $rules[] = 'max:' . $column->getLength();
        }

        if (in_array($columnType, ['integer', 'bigint', 'smallint'])) {
            $rules[] = 'integer';
        }

        if (in_array($columnType, ['float', 'decimal'])) {
            $rules[] = 'numeric';
        }

        return $rules;
    }
}
