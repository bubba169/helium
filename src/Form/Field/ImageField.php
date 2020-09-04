<?php namespace Helium\Form\Field;

use Helium\Form\Field\Field;

class ImageField extends Field
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->mergeConfig([
            'view' => 'helium::input.image',
            'disk' => 'local',
        ]);
    }
}
