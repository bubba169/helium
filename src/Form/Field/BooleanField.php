<?php namespace Helium\Form\Field;

use Helium\Form\Field\Field;

class BooleanField extends Field
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->mergeConfig([
            'class' => [
                'custom-control-input',
                'form-control'
            ],
            'labelClass' => [
                'custom-control-label',
            ],
            'view' => 'helium::input.boolean',
        ]);
    }
}
