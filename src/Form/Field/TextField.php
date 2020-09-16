<?php namespace Helium\Form\Field;

use Helium\Form\Field\Field;

class TextField extends Field
{
    public function __construct()
    {
        parent::__construct();
        $this->mergeConfig([
            'class' => [
                'form-control'
            ]
        ]);
    }
}
