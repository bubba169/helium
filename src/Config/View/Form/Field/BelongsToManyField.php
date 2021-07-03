<?php

namespace Helium\Config\View\Form\Field;

use Exception;
use Helium\Config\Entity;
use Helium\Config\View\Form\Field\MulticheckField;
use Helium\Handler\Options\RelatedOptionsHandler;
use Helium\Handler\Save\BelongsToManySaveHandler;
use Helium\Handler\Value\RelatedKeysValueHandler;

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
            case 'valueHandler':
                return RelatedKeysValueHandler::class;
        }

        return parent::getDefault($key);
    }
}
