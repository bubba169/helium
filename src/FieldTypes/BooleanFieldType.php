<?php namespace Helium\FieldTypes;

use Helium\FieldTypes\FieldType;

class BooleanFieldType extends FieldType
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->mergeConfig([
            'class' => [
                'checkbox',
            ],
            'view' => 'helium::input.boolean',
        ]);
    }
}
