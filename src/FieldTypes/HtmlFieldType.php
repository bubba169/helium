<?php namespace Helium\FieldTypes;

use Helium\FieldTypes\FieldType;

class HtmlFieldType extends FieldType
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->mergeConfig([
            'view' => 'helium::input.html',
        ]);
    }
}
