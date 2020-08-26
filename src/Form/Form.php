<?php namespace Helium\Form;

use Illuminate\Support\Collection;

class Form
{
    /**
     * Fields
     *
     * @var Collection
     */
    protected $fields;

    /**
     * Gets the fields
     *
     * @return Collection
     */
    public function getFields() : Collection
    {
        return $this->fields;
    }

    /**
     * Sets the fields
     *
     * @param Collection $fields
     * @return self
     */
    public function setFields(Collection $fields) : self
    {
        $this->fields = $fields;
        return $this;
    }
}
