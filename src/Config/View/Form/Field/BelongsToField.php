<?php

namespace Helium\Config\View\Form\Field;

use Exception;
use Helium\Config\Entity;
use Helium\Config\View\Form\Field\SelectField;
use Helium\Handler\Options\RelatedOptionsHandler;

class BelongsToField extends SelectField
{
    /**
     * Constructor
     */
    public function __construct(array $field, Entity $entity)
    {
        if (!array_key_exists('relatedName', $field)) {
            throw new Exception("Relationship type field {$field['slug']} requires a relatedName value.");
        }

        parent::__construct($field, $entity);
    }

    /**
     * {@inheritDoc}
     *
     * Use text area view
     */
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'column':
                return $this->slug . '_id';
            case 'options':
                return RelatedOptionsHandler::class;
            case 'relationship':
                return $this->slug;
        }

        return parent::getDefault($key);
    }
}
