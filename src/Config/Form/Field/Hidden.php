<?php

namespace Helium\Config\Form\Field;

use Helium\Config\Form\Field\Field;

class Hidden extends Field
{
    /**
     * Set to a hidden type field
     */
    public function getType(): string
    {
        return 'hidden';
    }

    /**
     * {@inheritDoc}
     *
     * No label as the field is invisible
     */
    public function getLabel(): ?string
    {
        return null;
    }
}
