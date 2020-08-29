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
     * Sections
     *
     * @var array
     */
    protected $sections = [];

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

    /**
     * Gets the form sections
     *
     * @return array
     */
    public function getSections() : array
    {
        return $this->sections;
    }

    /**
     * Sets the form sections
     *
     * @param array $sections
     * @return this
     */
    public function setSections(array $sections) : self
    {
        $this->sections = $sections;
        return $this;
    }
}
