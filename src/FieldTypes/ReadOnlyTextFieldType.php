<?php namespace Helium\FieldTypes;

class ReadOnlyTextFieldType extends FieldType
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->mergeConfig([
            'view' => 'helium::input.read_only_text',
        ]);
    }
}
