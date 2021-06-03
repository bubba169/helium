<?php

namespace Helium\Config\Form\Field;

use Helium\Config\Form\Field\Field;

class TinyMCEField extends Field
{
    /**
     * {@inheritDoc}
     *
     * Use text area view
     */
    public function getDefault(string $key)
    {
        switch ($key) {
            case 'view':
                return 'helium::form-fields.tinymce';
            case 'profile':
                return 'standard';
        }

        return parent::getDefault($key);
    }

    /**
     *
     */
    public function getInputConfig() : array
    {
        if (is_array($this->profile)) {
            return $this->profile;
        }

        switch ($this->profile) {
            case 'standard':
                return [
                    'menubar' => false,
                    'toolbar' => 'code',
                    'branding' => false,
                    'plugins' => 'code',
                ];
        }

        return [];
    }
}
