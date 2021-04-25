<?php

namespace Helium\Config\Form\Field;

use Exception;
use Helium\Config\Entity;
use Helium\Form\RelatedOptionsHandler;
use Helium\Config\Form\Field\SelectField;

class BelongsToField extends SelectField
{
    /**
     * Constructor
     */
    public function __construct(array $field, Entity $entity)
    {
        if (!array_key_exists('related_name', $field)) {
            throw new Exception('Relationship type field requires a related_name value.');
        }

        if (!array_key_exists('related_model', $field)) {
            throw new Exception('Relationship type field requires a related_model value.');
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
            case 'related_id':
                return '{entry.id}';
            case 'relationship':
                return $this->slug;
        }

        return parent::getDefault($key);
    }
}
