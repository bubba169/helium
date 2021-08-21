<?php

namespace Helium\Config\View\Form\Field;

use Helium\Config\View\Form\Field\Field;
use Helium\Handler\Field\Save\HtmlSaveHandler;

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
            case 'template':
                return 'helium::form-fields.tinymce';
            case 'profile':
                return 'standard';
            case 'saveHandler':
                return HtmlSaveHandler::class;
            case 'apikey':
                return config('helium.tiny.apikey');
            case 'scripts':
                return [
                    'https://cdn.tiny.cloud/1/' . $this->apikey . '/tinymce/5/tinymce.min.js' => [
                        'referrerpolicy' => 'origin'
                    ]
                ];
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
