<?php

namespace Helium\Config\Form\Field;

use Helium\Config\Entity;
use Helium\Traits\HasConfig;
use Illuminate\Support\Str;

class Field
{
    use HasConfig;

    /**
     * The current entity config
     */
    protected Entity $entity;

    /**
     * Constructor
     */
    public function __construct(array $field, Entity $entity)
    {
        $this->mergeConfig($field);
        $this->entity = $entity;
        $this->attributes = array_normalise_keys($this->attributes);
    }

    /**
     * Sensible defaults
     */
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'name':
            case 'id':
            case 'column':
                return $this->slug;
            case 'type':
                return 'text';
            case 'label':
                return Str::title(str_humanise($this->slug));
            case 'value':
                return '{entry.' . $this->column . '}';
            case 'rules':
                return $this->required ? ['required'] : [];
            case 'view':
                return 'helium::form-fields.input';
            case 'autocomplete':
                return 'off';
            case 'attributes':
                return [];
        }

        return null;
    }
}
