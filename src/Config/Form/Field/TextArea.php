<?php

namespace Helium\Config\Form\Field;

use Helium\Config\Form\Field\BaseField;

class TextArea extends BaseField
{
    protected function defaultView(): string
    {
        return 'helium::form-fields.textarea';
    }
}
