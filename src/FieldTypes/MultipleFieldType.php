<?php namespace Helium\FieldTypes;

use Helium\FieldTypes\SelectFieldType;

class MultipleFieldType extends SelectFieldType
{
    /**
     * COnstructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->mergeConfig([
            'class' => [
                'choices-input'
            ],
            'attributes' => [
                'multiple' => true
            ],
        ]);
    }
}
