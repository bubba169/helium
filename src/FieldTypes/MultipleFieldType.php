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
            'view' => 'helium::input.multiple'
        ]);
    }

    /**
     * Gets the original field name without the []
     *
     * @return string
     */
    public function getFieldName() : string
    {
        return parent::getName();
    }

    /**
     * @inheritDoc
     */
    public function getName() : string
    {
        return parent::getName() . '[]';
    }

    /**
     * {@inheritDoc}
     */
    public function getValue()
    {
        $value = parent::getValue();
        if (is_string($value)) {
            return json_decode($value);
        }

        return $value;
    }
}
