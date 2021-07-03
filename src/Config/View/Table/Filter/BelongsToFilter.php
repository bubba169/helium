<?php

namespace Helium\Config\View\Table\Filter;

use Exception;
use Helium\Config\Entity;
use Helium\Config\View\Table\Filter\Filter;
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

        parent::__construct($field, $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'filterHandler':
                return RelationshipFilterHandler::class;
            case 'options':
                return RelatedOptionsHandler::class;
            case 'optionsHandlerParams':
                return [
                    // Pass an empty model to resolve relationships on
                    'entry' => new $this->entity->model(),
                ];
            case 'template':
                return 'helium::form-fields.select';
            case 'relationship':
                return $this->slug;
        }

        return parent::getDefault($key);
    }
}
