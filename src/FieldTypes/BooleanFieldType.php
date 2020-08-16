<?php namespace Helium\FieldTypes;

use Helium\FieldTypes\FieldType;

class BooleanFieldType extends FieldType
{
    protected $view = 'helium::input.boolean';

    public function __construct()
    {
        parent::__construct();
        $this->addClass('checkbox');
    }
}
