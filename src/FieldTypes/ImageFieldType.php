<?php namespace Helium\FieldTypes;

use Helium\FieldTypes\FieldType;

class ImageFieldType extends FieldType
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->mergeConfig([
            'view' => 'helium::input.image',
            'disk' => 'local',
        ]);
    }
}
