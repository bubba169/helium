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
        switch ($this->column['type']) {
            case 'boolean':
                return [
                    'type' => BooleanFieldType::class,
                ];
            case 'integer':
            case 'smallint':
            case 'bigint':
            case 'float':
            case 'decimal':
                return [
                    'type' => StringFieldType::class,
                    'config' => [
                        'attributes' => [
                            'type' => 'number',
                        ]
                    ],
                ];
            case 'date':
            case 'date_immutable':
                return [
                    'type' => DateFieldType::class,
                ];
            case 'datetime':
            case 'datetime_immutable':
                return [
                    'type' => DateFieldType::class,
                ];
        }

        return [
            'type' => StringFieldType::class,
            'config' => [
                'attributes' => [
                    'maxlength' => $this->column['length']
                ]
            ],
        ];
    }
}
