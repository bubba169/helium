<?php namespace Helium\Form\Field;

use Helium\Form\Field\Field;

class ReadOnlyTextField extends Field
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->mergeConfig([
            'view' => 'helium::input.read_only_text',
        ]);
    }
}
