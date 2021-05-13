<?php

namespace Helium\Config\Form\Field;

use Exception;
use Helium\Config\Entity;
use Helium\Config\Form\Field\MulticheckField;
use Helium\Handler\Options\RelatedOptionsHandler;
use Helium\Handler\Save\BelongsToManySaveHandler;

class BelongsToManyField extends MulticheckField
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
     */
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'options':
                return RelatedOptionsHandler::class;
            case 'relationship':
                return $this->slug;
            case 'saveHandler':
                return BelongsToManySaveHandler::class;
        }

        return parent::getDefault($key);
    }
}
