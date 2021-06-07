<?php

namespace Helium\Config\Form\Field;

use Helium\Config\Form\Field\Field;
use Helium\Handler\Save\HtmlSaveHandler;

class TinyField extends Field
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
            case 'saveHandler':
                return HtmlSaveHandler::class;
        }

        return parent::getDefault($key);
    }

    /**
     * If the current config is an array use that otherwise read the
     * profile from config
     */
    public function getInputConfig() : array
    {
        if (is_array($this->profile)) {
            return $this->profile;
        }

        return array_merge(
            config('helium.tiny.profiles.common', []),
            config('helium.tiny.profiles.' . $this->profile, [])
        );
    }
}
