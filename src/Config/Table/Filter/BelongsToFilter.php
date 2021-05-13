<?php

namespace Helium\Config\Table\Filter;

use Exception;
use Helium\Config\Entity;
use Helium\Config\Table\Filter\Filter;
use Helium\Handler\Options\RelatedOptionsHandler;
use Helium\Handler\Filter\RelationshipFilterHandler;

class BelongsToFilter extends Filter
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
            case 'handler':
                return RelationshipFilterHandler::class;
            case 'options':
                return RelatedOptionsHandler::class;
            case 'view':
                return 'helium::form-fields.select';
            case 'relationship':
                return $this->slug;
        }

        return parent::getDefault($key);
    }
}
