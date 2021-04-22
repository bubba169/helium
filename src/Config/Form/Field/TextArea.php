<?php

namespace Helium\Config\Form\Field;

use Illuminate\Support\Arr;
use Helium\Config\Form\Field\Field;

class TextArea extends Field
{
    public function getView(): string
    {
        return Arr::get('view', $this->formConfig, 'helium::form-fields.textarea');
    }
}
