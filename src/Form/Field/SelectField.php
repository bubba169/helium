<?php namespace Helium\Form\Field;

use Helium\Form\Field\Field;

class SelectField extends Field
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->mergeConfig([
            'class' => [
                'slimselect'
            ],
            'view' => 'helium::input.select',
            'placeholder' => trans('helium::input.select.placeholder'),
        ]);
    }

    /**
     * Gets the current options
     *
     * @return array
     */
    public function getOptions() : array
    {
        return $this->getConfig('options', []);
    }
}
