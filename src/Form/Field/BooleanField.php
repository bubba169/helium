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
                'checkbox',
            ],
            'view' => 'helium::input.boolean',
        ]);
    }
}
