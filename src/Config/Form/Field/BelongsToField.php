<?php

namespace Helium\Config\Form\Field;

use Exception;
use Helium\Config\Entity;
use Helium\Config\Form\Field\SelectField;
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

        if (!array_key_exists('relatedModel', $field)) {
            throw new Exception("Relationship type field {$field['slug']} requires a relatedModel value.");
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
            case 'relatedId':
                return 'id';
            case 'relationship':
                return $this->slug;
        }

        return parent::getDefault($key);
    }
}
