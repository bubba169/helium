<?php namespace Helium\FieldTypes;

class StringFieldType extends FieldType
{
    /**
     * @var string
     */
    protected $value;

    /**
     * Gets the current value
     *
     * @return string
     */
    public function getValue() : string
    {
        return $this->value;
    }

    /**
     * Sets the value
     *
     * @param string $value
     * @return void
     */
    public function setValue(string $value)
    {
        $this->value = $value;
    }
}
